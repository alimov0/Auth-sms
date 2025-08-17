<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Interfaces\Services\AuthServiceInterface;

class CleanUnverifiedUsersCommand extends Command
{
    protected $signature = 'users:cleanup';
    protected $description = 'Delete unverified users older than 3 days';

    public function __construct(protected AuthServiceInterface $authService)
    {
        parent::__construct();
    }

    public function handle()
    {
        $deleted = $this->authService->cleanUp();
        $this->info("Deleted {$deleted} unverified users.");
    }
}
