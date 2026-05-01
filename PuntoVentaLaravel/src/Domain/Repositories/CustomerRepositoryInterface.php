<?php

declare(strict_types=1);

namespace Domain\Repositories;

use Domain\Entities\Customer;

interface CustomerRepositoryInterface
{
    public function findById(int $customerId): ?Customer;

    public function findByDocumentNumber(string $documentNumber): ?Customer;

    /** @return Customer[] */
    public function findAll(): array;

    public function save(Customer $customerEntity): Customer;

    /**
     * Calcula el siguiente ID disponible consultando MAX(CustomerId) + 1.
     */
    public function nextId(): int;
}
