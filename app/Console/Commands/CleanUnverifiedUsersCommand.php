<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AuthService;

class CleanUnverifiedUsersCommand extends Command
{
    protected $signature = 'users:clean-unverified'
    protected $description = 'Deletes users who are not verified within 3 days';

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        parent::__construct();
        $this->authService = $authService;
    }

    public function handle(): int
    {
        $this->authService->cleanUnverifiedUsers();
        $this->info('Unverified users cleaned.');
        return 0;
    }
}
