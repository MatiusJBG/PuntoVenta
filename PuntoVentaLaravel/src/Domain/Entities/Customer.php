<?php

declare(strict_types=1);

namespace Domain\Entities;

class Customer
{
    public function __construct(
        private readonly int     $customerId,
        private readonly string  $documentNumber,
        private readonly string  $firstName,
        private readonly string  $lastName,
        private readonly ?string $phone,
        private readonly ?string $email,
        private readonly ?string $address,
        private readonly ?string $city,
    ) {}

    public function getCustomerId(): int     { return $this->customerId; }
    public function getDocumentNumber(): string { return $this->documentNumber; }
    public function getFirstName(): string   { return $this->firstName; }
    public function getLastName(): string    { return $this->lastName; }
    public function getPhone(): ?string      { return $this->phone; }
    public function getEmail(): ?string      { return $this->email; }
    public function getAddress(): ?string    { return $this->address; }
    public function getCity(): ?string       { return $this->city; }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function isConsumidorFinal(): bool
    {
        return $this->documentNumber === '9999999999';
    }
}
