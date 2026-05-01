<?php

declare(strict_types=1);

namespace Application\UseCases;

use Domain\Entities\Sale;
use Domain\Repositories\SaleRepositoryInterface;

class ListSalesUseCase
{
    public function __construct(
        private readonly SaleRepositoryInterface $saleRepository,
    ) {}

    /** @return Sale[] */
    public function execute(): array
    {
        return $this->saleRepository->findAll();
    }
}
