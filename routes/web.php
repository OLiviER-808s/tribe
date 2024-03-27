<?php

use App\Http\Controllers\Auth\GoogleOAuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/conversation/create', [ConversationController::class, 'create'])->name('conversation.create');
    Route::post('/conversation/create', [ConversationController::class, 'store'])->name('conversation.store');
    Route::post('/conversation/{uuid}/join', [ConversationController::class, 'join'])->name('conversation.join');

    Route::get('/chats', [ChatController::class, 'index'])->name('chats');
    Route::get('/chat/{uuid}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{uuid}/send-message', [MessageController::class, 'store'])->name('chat.send-message');
});

Route::get('/google/redirect', [GoogleOAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/google/callback', [GoogleOAuthController::class, 'callback'])->name('google.callback');

require __DIR__.'/auth.php';
