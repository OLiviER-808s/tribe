<?php

namespace App\Nova;

use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class TopicCategory extends Resource
{
    public static $model = \App\Models\TopicCategory::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Uuid')
                ->onlyOnDetail(),

            Text::make('Name')
                ->rules('required'),

            HasMany::make('Topics'),
        ];
    }
}
