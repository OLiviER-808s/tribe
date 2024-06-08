<?php

namespace App\Nova;

use App\Nova\Lenses\TopicsToReview;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Topic extends Resource
{
    public static $model = \App\Models\Topic::class;

    public static $title = 'label';

    public static $search = [
        'id', 'label',
    ];

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Uuid')
                ->onlyOnDetail(),

            Text::make('Label')
                ->rules('required'),

            BelongsTo::make('Category', 'category', TopicCategory::class)
                ->rules('required'),

            BelongsTo::make('Parent', 'parent', Topic::class)
                ->searchable()
                ->nullable(),

            Number::make('Level')
                ->exceptOnForms()
                ->rules('required'),

            Boolean::make('Active'),

            BelongsTo::make('Requested by', 'requestedBy', User::class)
                ->onlyOnDetail(),

            HasMany::make('Children', 'children', Topic::class),
        ];
    }

    public static function afterCreate(NovaRequest $request, Model $model): void
    {
        $model->refreshLevel();
    }

    public static function afterUpdate(NovaRequest $request, Model $model): void
    {
        $model->refreshLevel();
    }

    public function lenses(NovaRequest $request): array
    {
        return [
            new TopicsToReview(),
        ];
    }
}
