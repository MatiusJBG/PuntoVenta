<?php

declare(strict_types=1);

namespace Application\UseCases;

use Domain\Entities\Customer;
use Domain\Repositories\CustomerRepositoryInterface;

class ListCustomersUseCase
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository,
    ) {}

    /** @return Customer[] */
    public function execute(): array
    {
        return $this->customerRepository->findAll();
    }
}
