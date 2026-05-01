<?php

declare(strict_types=1);

namespace Application\DTOs;

readonly class RegisterSaleDTO
{
    /**
     * @param SaleItemDTO[] $saleItems
     */
    public function __construct(
        public int   $customerId,
        public int   $paymentMethodId,
        public array $saleItems,
    ) {}
}
