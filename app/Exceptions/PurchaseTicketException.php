<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PurchaseTicketException extends Exception
{
    public function report()
    {
        // return false;
    }

    public function render(Request $request): Response
    {
        return response()->error([
            'key' => 'purchase_failed',
            'message' => $this->getMessage(),
        ], 422);
    }

    public static function alreadyPurchased(): static
    {
        return new static(__('exception.ticket_purchase.already_purchased'));
    }

    public static function notFound(): static
    {
        return new static(__('exception.ticket_purchase.not_found'));
    }

    public static function insufficientBalance(): static
    {
        return new static(__('exception.ticket_purchase.insufficient_balance'));
    }

    // BU yarışma için artık bilet satın alamazsınız.
    public static function notAvailable(): static
    {
        return new static(__('exception.ticket_purchase.not_available'));
    }
}
