<?php

declare(strict_types=1);

namespace Domain\Entities;

class SaleDetail
{
    public function __construct(
        private readonly int   $saleDetailId,
        private readonly int   $saleId,
        private readonly int   $productId,
        private readonly int   $quantity,
        private readonly float $unitPrice,
    ) {}

    public function getSaleDetailId(): int { return $this->saleDetailId; }
    public function getSaleId(): int       { return $this->saleId; }
    public function getProductId(): int    { return $this->productId; }
    public function getQuantity(): int     { return $this->quantity; }
    public function getUnitPrice(): float  { return $this->unitPrice; }

    /**
     * Replica la columna GENERATED de MySQL: Quantity * UnitPrice.
     */
    public function calculateLineSubtotal(): float
    {
        return round($this->quantity * $this->unitPrice, 2);
    }
}
