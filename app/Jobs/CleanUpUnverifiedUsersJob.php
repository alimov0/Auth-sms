<?php

namespace App\Jobs;

use App\Interfaces\Services\AuthServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanUpUnverifiedUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    public function handle(AuthServiceInterface $authService)
    {
        $deleted = $authService->cleanUp();
        \Log::info("CleanUp Job ishladi. {$deleted} ta user o‘chirildi.");
    }
}
