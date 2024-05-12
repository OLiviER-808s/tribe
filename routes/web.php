<?php

use App\Http\Controllers\Auth\GoogleOAuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\DiscoverController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UsernameController;
use App\Http\Middleware\RequiresInterests;
use App\Http\Middleware\RequiresUsername;
use Illuminate\Support\Facades\Route;

// GOOGLE OAUTH
Route::get('/google/redirect', [GoogleOAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/google/callback', [GoogleOAuthController::class, 'callback'])->name('google.callback');

// USERNAME
Route::get('/check-username/{username}', [UsernameController::class, 'check'])->name('username.check');

// TOPICS
Route::get('/topics', [TopicController::class, 'index'])->name('topics');
Route::get('/topics/{uuid}/children', [TopicController::class, 'getChildren'])->name('topic.children');

// PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/profile/interests', [ProfileController::class, 'updateInterests'])->name('profile.interests.update');

    Route::get('/set-profile', [ProfileController::class, 'create'])->name('profile.create');
    Route::get('/set-interests', [ProfileController::class, 'interests'])->middleware(RequiresUsername::class)->name('profile.interests');
});

Route::middleware(['auth', 'verified', RequiresUsername::class, RequiresInterests::class])->group(function () {
    // DISCOVER
    Route::get('/discover', [DiscoverController::class, 'index'])->name('discover');

    // GLOBAL SEARCH
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // PROFILES
    Route::get('/profiles/{username}', [ProfileController::class, 'show'])->name('profile');

    // CONVERSATIONS
    Route::post('/conversations', [ConversationController::class, 'store'])->name('conversation.store');
    Route::get('/conversations/create', [ConversationController::class, 'create'])->name('conversation.create');
    Route::get('/conversations/{uuid}', [ConversationController::class, 'show'])->name('conversation');
    Route::post('/conversations/{uuid}/join', [ConversationController::class, 'join'])->name('conversation.join');

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
