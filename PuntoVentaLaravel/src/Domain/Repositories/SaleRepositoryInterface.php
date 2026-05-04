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

    /**
     * Operación atómica de anulación:
     * 1. Restaura el Stock de cada producto listado en los SaleDetails.
     * 2. Cambia el StatusId de la venta a 0 (Anulada).
     * Todo ocurre dentro de una única transacción de base de datos.
     */
    public function voidSaleWithStockRestoration(Sale $saleToVoid): void;
}
