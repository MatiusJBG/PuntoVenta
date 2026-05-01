<?php

declare(strict_types=1);

namespace Application\DTOs;

readonly class RegisterCustomerDTO
{
    public function __construct(
        public string  $documentNumber,
        public string  $firstName,
        public string  $lastName,
        public ?string $phone   = null,
        public ?string $email   = null,
        public ?string $address = null,
        public ?string $city    = null,
    ) {}
}
