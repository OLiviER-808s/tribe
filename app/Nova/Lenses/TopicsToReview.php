<?php

namespace App\Nova\Lenses;

use App\Nova\User;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Nova;

class TopicsToReview extends Lens
{
    public static $search = [
        'id', 'label',
    ];

    public static function query(LensRequest $request, $query)
    {
        return $query->where('active', false);
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make(Nova::__('ID'), 'id')->sortable(),

            Text::make('Label')
                ->rules('required'),

            BelongsTo::make('Requested by', 'requestedBy', User::class),
        ];
    }

    public function uriKey()
    {
        return 'topics-to-review';
    }
}
