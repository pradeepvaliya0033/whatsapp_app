<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\ProviderController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Entity Management Routes
Route::prefix('entities')->group(function () {
    Route::get('/', [EntityController::class, 'index']);
    Route::post('/', [EntityController::class, 'store']);
    Route::get('/{uuid}', [EntityController::class, 'show']);
    Route::put('/{uuid}', [EntityController::class, 'update']);
    Route::delete('/{uuid}', [EntityController::class, 'destroy']);
    Route::post('/{uuid}/providers', [EntityController::class, 'addProvider']);
});

// Provider Management Routes
Route::prefix('providers')->group(function () {
    Route::get('/', [ProviderController::class, 'index']);
    Route::post('/', [ProviderController::class, 'store']);
    Route::get('/{uuid}', [ProviderController::class, 'show']);
    Route::put('/{uuid}', [ProviderController::class, 'update']);
    Route::delete('/{uuid}', [ProviderController::class, 'destroy']);
    Route::post('/{uuid}/test', [ProviderController::class, 'test']);
});

// WhatsApp Business API Routes
Route::prefix('whatsapp')->group(function () {
    // Message sending routes
    Route::post('/send-message', [WhatsAppController::class, 'sendMessage']);
    Route::post('/send-text', [WhatsAppController::class, 'sendTextMessage']);

    // Template management routes
    Route::post('/templates', [WhatsAppController::class, 'createTemplate']);
    Route::get('/templates', [WhatsAppController::class, 'getTemplateStatuses']);
    Route::get('/templates/{templateId}', [WhatsAppController::class, 'getTemplate']);
    Route::delete('/templates/{templateId}', [WhatsAppController::class, 'deleteTemplate']);

    // Message status and history routes
    Route::get('/messages/{messageId}/status', [WhatsAppController::class, 'getMessageStatus']);
    Route::get('/messages', [WhatsAppController::class, 'getMessageRequests']);
});
