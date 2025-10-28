<?php

namespace Database\Seeders;

use App\Enum\PlannedCompetitionStatus;
use App\Models\PlannedCompetition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlannedCompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*PlannedCompetition::firstOrCreate(['title' => '1Min',],
            [
                'uuid' => Str::uuid(),
                'title' => '1Min',
                'cost_percentage' => 12,
                'min_purchased_ticket_user' => 2,
                'interval_minutes' => 5,
                'status' => PlannedCompetitionStatus::Passive,
                'real_time_count' => 1,
                'ticket_count' => 200,
                'ticket_amount' => 100,
                'min_ticket_number' => 0,
                'max_ticket_number' => 10,
                'octet' => 6,
                'daily_limit' => 24,
                'manipulate_wait_secs_after_bot' => 5,
                'manipulate_wait_secs_after_user' => 5,
                'cancellation_time_limit' => 15,
            ]);

        PlannedCompetition::firstOrCreate(['title' => '12Hours',],
            [
                'uuid' => Str::uuid(),
                'title' => '12Hours',
                'cost_percentage' => 12,
                'min_purchased_ticket_user' => 1,
                'interval_minutes' => 5,
                'planned_finish_at' => now()->addHours(12),
                'status' => PlannedCompetitionStatus::Passive,
                'real_time_count' => 1,
                'ticket_amount' => 1000,
                'ticket_count' => 1000,
                'min_ticket_number' => 0,
                'max_ticket_number' => 10,
                'octet' => 6,
                'daily_limit' => 24,
                'manipulate_wait_secs_after_bot' => 5,
                'manipulate_wait_secs_after_user' => 5,
                'cancellation_time_limit' => 15,
            ]);*/

        PlannedCompetition::firstOrCreate(['title' => 'Piyango 1',],
            [
                'uuid' => Str::uuid(),
                'title' => 'Hızlı Piyango',
                'cost_percentage' => 10, // komisyon oranı, ne kadarı bizim
                'min_purchased_ticket_user' => 1, // bu rakamin altında bilet satılırsa iade ve iptal gerçekleşir. // TODO: bunu min user sayısı ile değiştirmek lazım.
                'interval_minutes' => 1, // Kaç dakikada bir çekiliş yapacak
                'planned_finish_at' => null,// now()->addHours(12), // planned competition ne zaman bitsin?
                'status' => PlannedCompetitionStatus::Active,
                'real_time_count' => 1, // aynı anda kaç yarışma başlatılsın
                'ticket_amount' => 500, // ticket ücreti
                'ticket_count' => 500, // Kaç bilet yaratsın
                'min_ticket_number' => 0, // ticket hanesi minimum kaç haneli olsun
                'max_ticket_number' => 9, // ticket hanesi maximum kaç haneli olsun
                'octet' => 6, // biletler kaç haneli olsun
                'daily_limit' => 10,
                'manipulate_wait_secs_after_bot' => 5,
                'manipulate_wait_secs_after_user' => 5,
                'cancellation_time_limit' => 15,
            ]);
            /*
            PlannedCompetition::firstOrCreate(['title' => 'Günlük Piyango',],
            [
                'uuid' => Str::uuid(),
                'title' => 'Günlük Piyango',
                'cost_percentage' => 10, // komisyon oranı, ne kadarı bizim
                'min_purchased_ticket_user' => 1, // bu rakakmın altında bilet satılırsa iade ve iptal gerçekleşir. // TODO: bunu min user sayısı ile değiştirmek lazım.
                'interval_minutes' => 1440, // Kaç dakikada bir çekiliş yapacak
                'planned_finish_at' => null,// now()->addHours(12), // planned competition ne zaman bitsin?
                'status' => PlannedCompetitionStatus::Active,
                'real_time_count' => 1, // aynı anda kaç yarışma başlatılsın
                'ticket_amount' => 50, // ticket ücreti
                'ticket_count' => 200, // Kaç bilet yaratsın
                'min_ticket_number' => 0, // ticket hanesi minimum kaç haneli olsun
                'max_ticket_number' => 9, // ticket hanesi maximum kaç haneli olsun
                'octet' => 6, // biletler kaç haneli olsun
                'daily_limit' => 1,
                'manipulate_wait_secs_after_bot' => 5,
                'manipulate_wait_secs_after_user' => 5,
                'cancellation_time_limit' => 15,
            ]);

            PlannedCompetition::firstOrCreate(['title' => 'Bilet Almayın İptal Olmalık Piyango',],
            [
                'uuid' => Str::uuid(),
                'title' => 'Bilet Almayın İptal Olmalık Piyango',
                'cost_percentage' => 10, // komisyon oranı, ne kadarı bizim
                'min_purchased_ticket_user' => 80, // bu rakakmın altında bilet satılırsa iade ve iptal gerçekleşir. // TODO: bunu min user sayısı ile değiştirmek lazım.
                'interval_minutes' => 2, // Kaç dakikada bir çekiliş yapacak
                'planned_finish_at' => null,// now()->addHours(12), // planned competition ne zaman bitsin?
                'status' => PlannedCompetitionStatus::Active,
                'real_time_count' => 1, // aynı anda kaç yarışma başlatılsın
                'ticket_amount' => 25, // ticket ücreti
                'ticket_count' => 200, // Kaç bilet yaratsın
                'min_ticket_number' => 0, // ticket hanesi minimum kaç haneli olsun
                'max_ticket_number' => 9, // ticket hanesi maximum kaç haneli olsun
                'octet' => 6, // biletler kaç haneli olsun
                'daily_limit' => 5,
                'manipulate_wait_secs_after_bot' => 5,
                'manipulate_wait_secs_after_user' => 5,
                'cancellation_time_limit' => 15,
            ]);

            PlannedCompetition::firstOrCreate(['title' => 'Haftalık Büyük Piyango',],
            [
                'uuid' => Str::uuid(),
                'title' => 'Haftalık Büyük Piyango',
                'cost_percentage' => 10, // komisyon oranı, ne kadarı bizim
                'min_purchased_ticket_user' => 1, // bu rakakmın altında bilet satılırsa iade ve iptal gerçekleşir. // TODO: bunu min user sayısı ile değiştirmek lazım.
                'interval_minutes' => 1440 * 7, // Kaç dakikada bir çekiliş yapacak
                'planned_finish_at' => null,// now()->addHours(12), // planned competition ne zaman bitsin?
                'status' => PlannedCompetitionStatus::Active,
                'real_time_count' => 1, // aynı anda kaç yarışma başlatılsın
                'ticket_amount' => 250, // ticket ücreti
                'ticket_count' => 200, // Kaç bilet yaratsın
                'min_ticket_number' => 0, // ticket hanesi minimum kaç haneli olsun
                'max_ticket_number' => 9, // ticket hanesi maximum kaç haneli olsun
                'octet' => 6, // biletler kaç haneli olsun
                'daily_limit' => 1,
                'manipulate_wait_secs_after_bot' => 5,
                'manipulate_wait_secs_after_user' => 5,
                'cancellation_time_limit' => 15,
            ]);*/
    }
}
