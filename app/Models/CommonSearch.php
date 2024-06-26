<?php

namespace App\Models;

use App\Traits\Searchable;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommonSearch extends Model
{
    use HasFactory, Searchable, UsesUuid;

    protected $fillable = [
        'search_term',
    ];

    public $searchResultType = 'search_term';

    public function viewModel()
    {
        return [
            'uuid' => $this->uuid,
            'term' => $this->search_term,
        ];
    }
}
