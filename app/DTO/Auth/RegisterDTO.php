<?php 
namespace App\DTO\Auth;

class RegisterDTO
{
    public string $name;
    public string $last_name;
    public string $phone;
    public ?string $avatar;

    public function __construct(array $data)
    {
        $this->name      = $data['name'];
        $this->last_name = $data['last_name'];
        $this->phone     = $data['phone'];
        $this->avatar    = $data['avatar'] ?? null;
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}
