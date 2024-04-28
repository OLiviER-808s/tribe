<?php

use App\Http\Controllers\Auth\GoogleOAuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UsernameController;
use App\Http\Middleware\RequiresInterests;
use App\Http\Middleware\RequiresUsername;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/google/redirect', [GoogleOAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/google/callback', [GoogleOAuthController::class, 'callback'])->name('google.callback');

Route::get('/check-username/{username}', [UsernameController::class, 'check'])->name('username.check');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/profile/interests', [ProfileController::class, 'updateInterests'])->name('profile.interests.update');

    Route::get('/set-profile', [ProfileController::class, 'create'])->name('profile.create');
    Route::get('/set-interests', [ProfileController::class, 'interests'])->middleware(RequiresUsername::class)->name('profile.interests');
});

Route::middleware(['auth', 'verified', RequiresUsername::class, RequiresInterests::class])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // CONVERSATIONS
    Route::get('/conversation/create', [ConversationController::class, 'create'])->name('conversation.create');
    Route::post('/conversation/create', [ConversationController::class, 'store'])->name('conversation.store');
    Route::post('/conversation/{uuid}/join', [ConversationController::class, 'join'])->name('conversation.join');

    // CHATS
    Route::get('/chats', [ChatController::class, 'index'])->name('chats');
    Route::get('/chats/{uuid}', [ChatController::class, 'show'])->name('chat.show');
    Route::put('/chats/{uuid}', [ChatController::class, 'update'])->name('chat.update');
    Route::patch('/chats/{uuid}/archive', [ChatController::class, 'archive'])->name('chat.archive');
    Route::patch('/chats/{uuid}/unarchive', [ChatController::class, 'unarchive'])->name('chat.unarchive');
    Route::delete('/chats/{uuid}/leave', [ChatController::class, 'leave'])->name('chat.leave');

    // CHAT MEMBERS
    Route::delete('/chats/{chatUuid}/members/{memberUuid}/remove', [ChatController::class, 'removeMember'])->name('chat.remove-member');
    Route::patch('/chats/{chatUuid}/members/{memberUuid}/assign-admin', [ChatController::class, 'assignAdmin'])->name('chat.assign-admin');

    // MESSAGES
    Route::post('/chats/{uuid}/send-message', [MessageController::class, 'store'])->name('chat.send-message');
    Route::post('/chats/{uuid}/typing', [MessageController::class, 'toggleTyping'])->name('chat.typing');
    Route::get('/messages/{messageUuid}/download/{fileUuid}', [MessageController::class, 'downloadAttachment'])->name('message.download-attachment');

    // SETTINGS
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('/settings/account', [SettingsController::class, 'account'])->name('settings.account');
    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');

    Route::patch('/settings/theme/{theme}', [SettingsController::class, 'setTheme'])->name('settings.theme');
});

require __DIR__.'/auth.php';
