<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        $result = array_merge(parent::share($request), [
            'auth' => [
                'user' => $user,
                'isOAuthUser' => ! $user?->password,
            ],
            'flash' => [
                'error' => fn () => $request->session()->get('error'),
                'success' => fn () => $request->session()->get('success')
            ]
        ]);

        if ($user instanceof User) {
            $result = array_merge($result, [
                'profile' => $user?->viewModel(true, true),
                'unreadChats' => $user?->unread_chats,
                'theme' => $user?->settings?->theme,
            ]);
        }

        return $result;
    }
}
