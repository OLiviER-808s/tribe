<?php

namespace App\Nova\Lenses;

use App\Nova\Topic;
use App\Nova\User;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\LensRequest;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Lenses\Lens;
use Laravel\Nova\Nova;

class ReportedConversations extends Lens
{
    public static $search = [];

    public static function query(LensRequest $request, $query)
    {
        return $query->where('active', true)->whereHas('reports', function ($query) {
            return $query->where('resolved', false);
        });
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make(Nova::__('ID'), 'id')->sortable(),

            Text::make('Title'),

            BelongsTo::make('Topic', 'topic', Topic::class)
                ->filterable(),

            BelongsTo::make('Created By', 'createdByUser', User::class),

            Date::make('Created At'),

            Number::make('Reports', function () {
                return $this->reports->where('resolved', false)->count();
            }),

            Text::make('Reason', function () {
                $reasons = [];

                foreach ($this->reports as $report) {
                    $category = $report->category->name;

                    if (isset($reasons[$category])) {
                        $reasons[$category] += 1;
                    } else {
                        $reasons[$category] = 1;
                    }
                }

                return array_search(max($reasons), $reasons);
            }),
        ];
    }

    public function actions(NovaRequest $request)
    {
        return parent::actions($request);
    }

    public function uriKey()
    {
        return 'reported-conversations';
    }
}
