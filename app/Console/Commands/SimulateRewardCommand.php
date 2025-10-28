<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SimulateRewardCommand extends Command
{
    // Artisan komut imzası: parametreler dışarıdan CLI ile alınır
    protected $signature = 'simulate:reward
        {--pot=100000}                       // Toplam toplanan para
        {--cost=20}                          // Sistem maliyeti yüzdesi
        {--participants=1000}               // Toplam katılımcı sayısı
        {--top-x-count=5}                   // Ödül alacak kazanan sayısı (Winners)
        {--top-x-share=40}                  // Winners grubunun alacağı net pot yüzdesi
        {--guaranteed-percent=50}           // En az amorti alacak kullanıcı yüzdesi
        {--iterations=5}                    // Simülasyon tekrarı
        {--skew=1.75}                       // Ödül dağılımında eğrilik
        {--jitter=0.15}                     // Rastgelelik payı
        {--enforce-amorti}';                // Amorti zorunluluğu aktif mi?

    protected $description = 'Ödül dağıtım algoritmasını parametrelerle test eden simülasyon komutu';

    public function handle(): int
    {
        // Girdi parametrelerini al
        $pot = (float) $this->option('pot');
        $costPercent = (float) $this->option('cost');
        $participants = (int) $this->option('participants');
        $winnersCount = (int) $this->option('top-x-count');
        $winnersSharePercent = (float) $this->option('top-x-share');
        $guaranteedPercent = (float) $this->option('guaranteed-percent');
        $iterations = (int) $this->option('iterations');
        $skew = (float) $this->option('skew');
        $jitter = (float) $this->option('jitter');
        $enforceAmorti = $this->option('enforce-amorti');

        // Net dağıtılabilir tutar ve kişi başı ortalama katkı
        $netPot = $pot * ((100 - $costPercent) / 100.0);
        $avgInvest = $pot / $participants;
        $guaranteedCount = (int) ceil($participants * $guaranteedPercent / 100.0);

        $results = [];

        // Simülasyon döngüsü
        for ($i = 1; $i <= $iterations; $i++) {
            $iterationResult = [
                'iteration' => $i,
                'net_pot' => $netPot,
                'winners' => [],
                'others' => [],
                'total_distributed' => 0,
                'remaining_diff' => 0,
            ];

            // Tüm katılımcılar içinden winners seçilir
            $participantIds = range(1, $participants);
            $winnerIds = $this->pickUnique($participantIds, $winnersCount);
            $winnerWeights = $this->generateDescendingWeights($winnersCount, $skew, $jitter, 0.03); // min %3
            $winnerAmount = $netPot * ($winnersSharePercent / 100.0);
            $winnerPayouts = array_map(fn($w) => $w * $winnerAmount, $winnerWeights);

            // En az ortalama yatırım kadar almalı
            foreach ($winnerPayouts as &$p) {
                if ($p < $avgInvest) $p = $avgInvest;
            }
            unset($p);

            // Geriye kalan havuz (others için kullanılacak)
            $winnerPaid = array_sum($winnerPayouts);
            $restPool = $netPot - $winnerPaid;

            // Eğer winners tüm potu aldıysa bu tur atlanır
            if ($restPool <= 0) continue;

            // Others seçimi (winners dışındaki katılımcılardan garanti oranı kadar kişi)
            $nonWinnerIds = array_values(array_diff($participantIds, $winnerIds));
            $guaranteedOthers = max(0, min(count($nonWinnerIds), $guaranteedCount - $winnersCount));
            $othersIds = $this->pickUnique($nonWinnerIds, $guaranteedOthers);
            $otherWeights = $this->generateDescendingWeights(count($othersIds), $skew, $jitter);
            $otherPayouts = array_map(fn($w) => $w * $restPool, $otherWeights);

            // Others, hiçbir zaman winners’ın en az kazananından fazla kazanamaz
            $minWinner = min($winnerPayouts);
            foreach ($otherPayouts as &$p) {
                if ($p > $minWinner) $p = $minWinner;
            }
            unset($p);

            // Amorti garantisi varsa
            if ($enforceAmorti && count($otherPayouts) > 0) {
                foreach ($otherPayouts as &$p) {
                    if ($p < $avgInvest) $p = $avgInvest;
                }
                unset($p);

                // Gerekirse others amortileri kırpılarak net pota sığdırılır
                $totalDistributed = array_sum($winnerPayouts) + array_sum($otherPayouts);
                if ($totalDistributed > $netPot) {
                    $availableRest = $netPot - array_sum($winnerPayouts);
                    $sum = array_sum($otherPayouts);
                    foreach ($otherPayouts as &$p) {
                        $p = ($sum > 0) ? ($p / $sum) * $availableRest : 0;
                    }
                    unset($p);
                }
            }

            // Ödemeleri 10’un katına aşağı yuvarla
            foreach ($winnerPayouts as &$p) {
                $p = $this->roundDownToNearest10($p);
            }
            unset($p);

            foreach ($otherPayouts as &$p) {
                $p = $this->roundDownToNearest10($p);
            }
            unset($p);

            // Others hiçbir zaman winners’tan fazla olamaz (yuvarlamadan sonra da)
            $minWinnerPayout = min($winnerPayouts);
            foreach ($otherPayouts as &$p) {
                if ($p > $minWinnerPayout) $p = $minWinnerPayout;
            }
            unset($p);

            // Kalan farkı sırayla others'a 10 TL olarak dağıt
            $totalDistributed = array_sum($winnerPayouts) + array_sum($otherPayouts);
            $diff = $netPot - $totalDistributed;

            $distributable = $this->roundDownToNearest10($diff);
            $iUser = 0;
            while ($distributable >= 10 && $iUser < count($otherPayouts)) {
                $otherPayouts[$iUser] += 10;
                $distributable -= 10;
                $iUser++;
            }

            // Son hesaplamalar
            $totalDistributed = array_sum($winnerPayouts) + array_sum($otherPayouts);
            $diff = $netPot - $totalDistributed;

            // Sonuçları dizide topla
            foreach ($winnerIds as $index => $id) {
                $iterationResult['winners'][] = [
                    'user_id' => $id,
                    'rank' => $index + 1,
                    'amount' => $winnerPayouts[$index]
                ];
            }

            foreach ($othersIds as $index => $id) {
                $iterationResult['others'][] = [
                    'user_id' => $id,
                    'amount' => $otherPayouts[$index] ?? 0
                ];
            }

            $iterationResult['total_distributed'] = $totalDistributed;
            $iterationResult['remaining_diff'] = $diff;

            $results[] = $iterationResult;
        }

        // Laravel-style debug çıktısı
        dd($results);
        return self::SUCCESS;
    }

    // Belirli sayıda benzersiz kullanıcı seçimi
    private function pickUnique(array $source, int $count): array
    {
        shuffle($source);
        return array_slice($source, 0, $count);
    }

    // Azalan şekilde ağırlık üret (en büyük ödülü başa ver), minimum oran garantili
    private function generateDescendingWeights(int $count, float $skew, float $jitter, float $minShare = 0.03): array
    {
        if ($count <= 0) return [];

        $weights = [];
        for ($i = 1; $i <= $count; $i++) {
            $base = 1 / pow($i, $skew);
            $noise = 1 + $this->uniform(-$jitter, $jitter);
            $weights[] = max(1e-6, $base * $noise);
        }

        rsort($weights);
        $sum = array_sum($weights);
        $normalized = array_map(fn($w) => $w / $sum, $weights);

        $adjusted = [];
        foreach ($normalized as $w) {
            $adjusted[] = max($w, $minShare);
        }

        $adjustedSum = array_sum($adjusted);
        return array_map(fn($w) => $w / $adjustedSum, $adjusted);
    }

    // [a, b] aralığında rastgele sayı
    private function uniform(float $a, float $b): float
    {
        return $a + (mt_rand() / mt_getrandmax()) * ($b - $a);
    }

    // En yakın alt 10’luk değere yuvarlama
    private function roundDownToNearest10(float $amount): float
    {
        return floor($amount / 10) * 10;
    }
}
