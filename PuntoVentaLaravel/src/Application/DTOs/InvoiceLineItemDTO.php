<?php

declare(strict_types=1);

namespace Application\DTOs;

/**
 * Representa una línea individual de la factura (un ítem vendido).
 */
readonly class InvoiceLineItemDTO
{
    public function __construct(
        public int    $productCode,
        public string $productDescription,
        public int    $quantity,
        public float  $unitPrice,
        public float  $lineSubtotal,
    ) {}
}
