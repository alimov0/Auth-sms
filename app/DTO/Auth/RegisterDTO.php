<?php 
namespace App\DTO\Auth;

class RegisterDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $last_name,
        public readonly string $phone,
        public readonly ?string $avatar = null,
    ) {}
    
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            last_name: $data['last_name'],
            phone: $data['phone'],
            avatar: $data['avatar'] ?? null,
        );
    }
}
