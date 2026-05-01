<?php

declare(strict_types=1);

namespace Application\DTOs;

readonly class SaleItemDTO
{
    public function __construct(
        public int   $productId,
        public int   $quantity,
        public float $unitPrice,
    ) {}
}
