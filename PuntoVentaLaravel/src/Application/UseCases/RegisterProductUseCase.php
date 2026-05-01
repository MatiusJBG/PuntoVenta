<?php

declare(strict_types=1);

namespace Application\UseCases;

use Application\DTOs\RegisterProductDTO;
use Domain\Entities\Product;
use Domain\Repositories\ProductRepositoryInterface;
use DomainException;

class RegisterProductUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(RegisterProductDTO $registerProductDTO): Product
    {
        $this->ensureProductNameIsNotEmpty($registerProductDTO->name);
        $this->ensurePriceIsWithinAllowedRange($registerProductDTO->price);
        $this->ensureStockIsWithinAllowedRange($registerProductDTO->stock);

        $nextProductId = $this->productRepository->nextId();

        $productEntity = new Product(
            productId: $nextProductId,
            name:      trim($registerProductDTO->name),
            price:     $registerProductDTO->price,
            stock:     $registerProductDTO->stock,
        );

        return $this->productRepository->save($productEntity);
    }

    private function ensureProductNameIsNotEmpty(string $name): void
    {
        if (empty(trim($name))) {
            throw new DomainException("El nombre del producto no puede estar vacío.");
        }
    }

    private function ensurePriceIsWithinAllowedRange(float $price): void
    {
        if ($price <= 0 || $price > 1_000_000.00) {
            throw new DomainException(
                "El precio debe ser un valor positivo y no mayor a 1,000,000.00. Recibido: {$price}."
            );
        }
    }

    private function ensureStockIsWithinAllowedRange(int $stock): void
    {
        if ($stock < 0 || $stock > 100_000) {
            throw new DomainException(
                "El stock debe estar entre 0 y 100,000. Recibido: {$stock}."
            );
        }
    }
}
