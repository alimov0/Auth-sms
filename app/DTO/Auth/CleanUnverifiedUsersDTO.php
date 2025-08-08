<?php 
namespace App\DTO\Auth;

class CleanUnverifiedUsersDTO
{
    public \DateTime $thresholdDate;

    public function __construct()
    {
        $this->thresholdDate = now()->subDays(3);
    }
}
