<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PhoneOperatorDetect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:phone-operator-detect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phones = [

        ];

        foreach ($phones as $phone) {
            $this->request($phone);
            sleep(1);
        }
    }

    public function request($phone)
    {
        $step1 = Http
            ::withOptions([])
            ->withHeaders([])
            ->get('https://www.turkiye.gov.tr/btk-numara-tasima');

        $token = $this->getToken($step1->getBody()->getContents());

        $headers = [
            'Accept' => '*/*',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Accept-Language' => 'tr-TR,tr;q=0.9,en-US;q=0.8,en;q=0.7',
        ];

        $payload = [
            'txtMsisdn' => $phone,
            'token' => $token
        ];

        $step2 = Http::asForm()
            ->withOptions(['cookies' => $step1->cookies])
            ->withHeaders($headers)
            ->post('https://www.turkiye.gov.tr/btk-numara-tasima?submit', $payload)
            ->getBody()
            ->getContents();

        $data = $this->analyseData($step2);

        $this->info($data);
    }

    private function getToken($html) {
        $pattern = '/data-token="({[^}]+})"/';
        preg_match($pattern, $html, $matches);

        if (count($matches) === 2)
            return $matches[1];
        else
            return null;
    }

    private function analyseData($html) {

        if (preg_match('/işletmeci: (.*?)<\/div>/', $html, $matches)) {
            $operator = $matches[1];
            return $operator;
        } else {
            return "Unknown";
        }
    }
}
