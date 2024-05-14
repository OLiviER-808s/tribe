<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\CheckAge;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUser extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'dob' => ['required', 'date', new CheckAge],
            'password' => ['required', Password::defaults()],
        ];
    }
}
