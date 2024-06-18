<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Http\Requests\NovaRequest;

class ResolveReportedConversation extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Resolve';

    public function handle(ActionFields $fields, Collection $conversations)
    {
        foreach ($conversations as $conversation) {
            $conversation->resolveReports();

            if ($fields['deactivate']) {
                $conversation->active = false;
                $conversation->save();
            }
        }
    }

    public function fields(NovaRequest $request)
    {
        return [
            Boolean::make('Deactivate Conversation', 'deactivate'),
        ];
    }
}
