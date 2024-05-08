<?php

namespace App\Jobs;

use App\Models\CommonSearch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewSearchTerm implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $term;
    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct($term, $user)
    {
        $this->term = $term;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $searchTerm = CommonSearch::firstOrCreate([ 'search_term' => $this->term ]);
        $searchTerm->addSearchRecord($this->user);
    }
}
