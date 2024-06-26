<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\CheckAge;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:160',
            'bio' => 'required|string|max:250',
            'username' => ['required', 'string', 'min:3', 'max:25', Rule::unique(User::class)->ignore($this->user()->id)],
            'photo' => 'nullable|file',
            'location' => 'nullable|string',
            'dob' => ['required', 'date', new CheckAge],
            'next_route' => 'required|string',
        ];
    }
}
