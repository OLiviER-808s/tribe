<?php

namespace App\Nova;

use App\Nova\Actions\ResolveReportedConversation;
use App\Nova\Lenses\ReportedConversations;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Conversation extends Resource
{
    public static $model = \App\Models\Conversation::class;

    public static $title = 'title';
    public static $search = [
        'id', 'title'
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Uuid')
                ->onlyOnDetail(),

            Text::make('Title'),

            Textarea::make('Description'),

            BelongsTo::make('Topic', 'topic', Topic::class)
                ->searchable()
                ->filterable(),

            Text::make('Members', function () {
                return $this->chat->members->count() . '/' . $this->limit;
            }),

            Boolean::make('Active')
                ->filterable(),

            BelongsTo::make('Created By', 'createdByUser', User::class)
                ->exceptOnForms(),

            Date::make('Created At')
                ->exceptOnForms()
        ];
    }

    public function lenses(NovaRequest $request)
    {
        return [
            new ReportedConversations()
        ];
    }

    public function actions(NovaRequest $request)
    {
        return [
            new ResolveReportedConversation()
        ];
    }

    public static function authorizedToCreate(Request $request): false
    {
        return false;
    }

    public function authorizedToDelete(Request $request): false
    {
        return false;
    }
}
