<?php

declare(strict_types=1);

namespace Domain\Entities;

class Product
{
    public function __construct(
        private readonly int    $productId,
        private readonly string $name,
        private readonly float  $price,
        private int             $stock,
    ) {}

    public function getProductId(): int    { return $this->productId; }
    public function getName(): string      { return $this->name; }
    public function getPrice(): float      { return $this->price; }
    public function getStock(): int        { return $this->stock; }

    public function hasEnoughStock(int $requestedQuantity): bool
    {
        return $this->stock >= $requestedQuantity;
    }

    /**
     * Descuenta el stock vendido. Lanza excepción si el stock es insuficiente.
     */
    public function decreaseStock(int $quantitySold): void
    {
        if (! $this->hasEnoughStock($quantitySold)) {
            throw new \DomainException(
                "Stock insuficiente para el producto '{$this->name}'. "
                . "Disponible: {$this->stock}, solicitado: {$quantitySold}."
            );
        }

        $this->stock -= $quantitySold;
    }
}
