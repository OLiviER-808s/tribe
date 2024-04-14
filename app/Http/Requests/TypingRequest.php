<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'typing' => 'required|boolean'
        ];
    }
}
