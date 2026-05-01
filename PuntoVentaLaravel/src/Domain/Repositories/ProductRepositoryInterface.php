<?php

declare(strict_types=1);

namespace Domain\Repositories;

use Domain\Entities\Product;

interface ProductRepositoryInterface
{
    public function findById(int $productId): ?Product;

    /** @return Product[] */
    public function findAll(): array;

    public function save(Product $productEntity): Product;

    /**
     * Actualiza únicamente el campo Stock del producto dado.
     */
    public function updateStock(int $productId, int $newStock): void;

    /**
     * Calcula el siguiente ID disponible consultando MAX(ProductId) + 1.
     */
    public function nextId(): int;
}
