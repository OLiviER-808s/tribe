<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class User extends Resource
{
    public static $model = \App\Models\User::class;

    public static $title = 'name';

    public static $search = [
        'id', 'name', 'email',
    ];

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Uuid')
                ->onlyOnDetail(),

            Text::make('Name')
                ->sortable(),

            Text::make('Username')
                ->sortable(),

            Text::make('Email')
                ->sortable(),

            Text::make('Status'),

            Textarea::make('Bio'),

            Date::make('Date of Birth')
                ->onlyOnDetail(),

            Text::make('Location')
                ->onlyOnDetail(),

            BelongsToMany::make('Interests', 'interests', Topic::class),
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

    public function authorizedToUpdate(Request $request): false
    {
        return false;
    }
}
