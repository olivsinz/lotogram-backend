<?php

namespace App\Http\Resources\CompetitionAPI;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompetitionLotteryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'title' => $this->plannedCompetition->title,
            'octet' => $this->plannedCompetition->octet,
            'lottery_results' => $this->lotteryResults->map(function ($lotteryResult) {
                return [
                    'reward' => [
                        'id' => $lotteryResult->plannedCompetitionReward->uuid,
                        'percentage' => $lotteryResult->plannedCompetitionReward->percentage,
                    ],
                    'ticket' => [
                        'id' => $lotteryResult->ticket->uuid,
                        'number' => $this->formatAndShowNumber($lotteryResult->ticket->number, $lotteryResult->ticket->number_order),
                        'number_order' => $lotteryResult->ticket->number_order,
                        'user' => [
                            'id' => $lotteryResult->ticket->user->uuid,
                            'username' => $lotteryResult->ticket->user->username,
                        ]
                    ],
                ];
            }),
        ];
    }

    protected function formatAndShowNumber($number, $numberOrder): string
    {
        $numberWithoutHyphens = str_replace('-', '', $number);
        $substring = substr($numberWithoutHyphens, 0, $numberOrder);
        return implode('-', str_split($substring));
    }
}
