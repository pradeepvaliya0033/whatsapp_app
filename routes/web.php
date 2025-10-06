<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\FacebookController;
use App\Http\Controllers\ContactsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Default redirect
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Test Route
    Route::get('/test', function () {
        return view('test');
    })->name('test');

    // Database Test Route
    Route::get('/db-test', function () {
        return view('db-test');
    })->name('db-test');

    // Facebook Integration
    Route::prefix('facebook')->name('facebook.')->group(function () {
        Route::get('/settings', [FacebookController::class, 'settings'])->name('settings');
        Route::get('/redirect', [FacebookController::class, 'redirectToFacebook'])->name('redirect');
        Route::get('/callback', [FacebookController::class, 'handleFacebookCallback'])->name('callback');
        Route::post('/disconnect', [FacebookController::class, 'disconnect'])->name('disconnect');
        Route::post('/refresh', [FacebookController::class, 'refreshToken'])->name('refresh');
        Route::post('/update-page', [FacebookController::class, 'updateSelectedPage'])->name('update-page');
        Route::post('/test', [FacebookController::class, 'testConnection'])->name('test');
    });

    // WhatsApp Management
    Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
        Route::get('/messages', function () {
            return view('whatsapp.messages');
        })->name('messages');
        Route::get('/send', function () {
            return view('whatsapp.send');
        })->name('send');
        Route::post('/send-message', [WhatsAppController::class, 'sendMessage'])->name('send-message');
        Route::post('/send-text', [WhatsAppController::class, 'sendTextMessage'])->name('send-text');
    });

    // Template Management
    Route::prefix('templates')->name('templates.')->group(function () {
        Route::get('/', function () {
            // This would normally fetch from WhatsApp API, but for demo we'll use empty array
            $templates = [];
            return view('templates.index', compact('templates'));
        })->name('index');
        Route::get('/create', function () {
            return view('templates.create');
        })->name('create');
        Route::post('/', [WhatsAppController::class, 'createTemplate'])->name('store');
        Route::get('/{templateId}', [WhatsAppController::class, 'getTemplate'])->name('show');
        Route::delete('/{templateId}', [WhatsAppController::class, 'deleteTemplate'])->name('destroy');
    });

    // Contacts Management
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [ContactsController::class, 'index'])->name('index');
        Route::get('/create', [ContactsController::class, 'create'])->name('create');
        Route::post('/', [ContactsController::class, 'store'])->name('store');
        Route::get('/{uuid}/edit', [ContactsController::class, 'edit'])->name('edit');
        Route::put('/{uuid}', [ContactsController::class, 'update'])->name('update');
        Route::delete('/{uuid}', [ContactsController::class, 'destroy'])->name('destroy');
    });
});
