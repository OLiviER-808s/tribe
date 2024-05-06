<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsernameController extends Controller
{
    public function check($username)
    {
        $taken = User::where('username', $username)->exists();

        return [
            'taken' => $taken
        ];
    }
}
