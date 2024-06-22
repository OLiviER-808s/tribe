<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessage extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'uuid' => 'required|string',
            'content' => 'required|string',
            'reply_to_uuid' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'file',
            'active_uuids' => 'nullable|array',
        ];
    }
}
