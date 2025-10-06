<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppController;
// Removed Entity/Provider controllers and routes as part of cleanup

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

// Entity/Provider routes removed

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

    // Contacts for pickers
    Route::get('/contacts', [WhatsAppController::class, 'getContacts']);
});
