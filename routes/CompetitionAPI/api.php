<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\SupportController;
use App\Http\Controllers\Dashboard\MeController;
use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\CompetitionAPI\DepositController;
use App\Http\Controllers\CompetitionAPI\CompetitionController;
use App\Http\Controllers\CompetitionAPI\MaksiparaController;
use App\Http\Controllers\CompetitionAPI\WithdrawController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// TODO: bu rotalar cf'den rate limit ile korunmalı

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('forgot-password/verify', [AuthController::class, 'setForgotPasswordVerified']);

Route::prefix('supports')->group(function () {
    Route::get('/enum-list', [SupportController::class, 'enumList']);
    Route::get('/api-version', [SupportController::class, 'apiVersion']);
});

Route::prefix('competitions')->group(function () {
    Route::get('/', [CompetitionController::class, 'index']);
    Route::get('/{uuid}', [CompetitionController::class, 'show']);
    Route::get('/{uuid}/available', [CompetitionController::class, 'availableTickets']);
    Route::get('/{uuid}/purchased', [CompetitionController::class, 'purchasedTickets']);
    Route::get('/{uuid}/lottery', [CompetitionController::class, 'lottery']);
});

//FIXME: cf'den sadece maksi para ip adresi olarak fix'lenmeli
Route::post('maksipara', [MaksiparaController::class, 'gateway']);

Route::middleware(['auth:sanctum', 'verify.user'])->group(function ()
{

    Route::post('login/with-tfa', [AuthController::class, 'loginWithTfa']);
    Route::get('logout', [AuthController::class, 'logout']);

    Route::prefix('me')->group(function () {
        Route::get('/', [MeController::class, 'show']);
        Route::put('/', [MeController::class, 'update']);
        Route::put('/tfa/enable', [MeController::class, 'enableTFA']);
        Route::put('/tfa/disable', [MeController::class, 'disableTFA']);
        Route::get('/tfa/google-qrcode', [MeController::class, 'getGoogleQRCode']);
        Route::post('/tfa/send', [MeController::class, 'sendMailForTFACode']);
        Route::post('/email-verification/send', [MeController::class, 'sendMailForVerificationCode']);
        Route::post('/email-verification', [MeController::class, 'setEmailVerified']);
        Route::post('/tfa-session', [MeController::class, 'createTFASession']);
        Route::post('/tfa-session/send', [MeController::class, 'sendMailForTFASessionCode']);

        Route::get('tickets', [MeController::class, 'tickets']);
        Route::delete('tickets/{ticket_uuid}/cancel', [MeController::class, 'cancelTicket']);
        Route::get('transactions', [MeController::class, 'transactions']);
        Route::get('balance', [MeController::class, 'balances']);

        Route::get('notifications', [MeController::class, 'notifications']);
        Route::put('notifications/{uuid}/read', [MeController::class, 'readNotification']);

        Route::get('bonuses', [MeController::class, 'bonuses']);
    });

    Route::prefix('competitions')->group(function () {
        Route::post('/', [CompetitionController::class, 'store']);
        Route::post('/{uuid}/tickets', [CompetitionController::class, 'purchase']);
        Route::get('/{uuid}/purchased/me', [CompetitionController::class, 'purchasedMeTickets']);
    });

    Route::prefix('deposits')->group(function () {
        Route::get('/methods', [DepositController::class, 'methodList']);
        Route::post('/', [DepositController::class, 'store']);
    });

    Route::prefix('withdraws')->group(function () {
        Route::get('/methods', [WithdrawController::class, 'methodList']);
        Route::post('/virtual-wallets', [WithdrawController::class, 'storeVirtualWallet']);
        Route::post('/bank-transfer', [WithdrawController::class, 'storeBankTransfer']);
    });
});

