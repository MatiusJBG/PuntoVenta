<?php

declare(strict_types=1);

namespace Domain\Repositories;

use Domain\Entities\Sale;

interface SaleRepositoryInterface
{
    public function save(Sale $saleEntity): Sale;

    public function findById(int $saleId): ?Sale;

    /** @return Sale[] */
    public function findAll(): array;

    /**
     * Siguiente ID disponible para la tabla Sales.
     */
    public function nextId(): int;

    /**
     * Siguiente ID disponible para la tabla SaleDetails.
     */
    public function nextDetailId(): int;
}
