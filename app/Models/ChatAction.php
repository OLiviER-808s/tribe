<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatAction extends Model
{
    use HasFactory, UsesUuid;

    protected $fillable = [
        'chat_id',
        'text',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    public function viewModel()
    {
        return [
            'uuid' => $this->uuid,
            'text' => $this->text,
            'sent_at' => $this->created_at
        ];
    }
}
