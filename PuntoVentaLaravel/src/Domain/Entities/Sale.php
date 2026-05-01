<?php

declare(strict_types=1);

namespace Domain\Entities;

use DateTimeImmutable;

class Sale
{
    /** @var SaleDetail[] */
    private array $saleDetails;

    public function __construct(
        private readonly int               $saleId,
        private readonly int               $customerId,
        private readonly DateTimeImmutable $saleDate,
        private readonly int               $paymentMethodId,
        private readonly int               $statusId,
        private readonly float             $subtotal,
        private readonly float             $taxAmount,
        private readonly float             $total,
        SaleDetail                         ...$saleDetails,
    ) {
        $this->saleDetails = $saleDetails;
    }

    public function getSaleId(): int                { return $this->saleId; }
    public function getCustomerId(): int            { return $this->customerId; }
    public function getSaleDate(): DateTimeImmutable { return $this->saleDate; }
    public function getPaymentMethodId(): int       { return $this->paymentMethodId; }
    public function getStatusId(): int              { return $this->statusId; }
    public function getSubtotal(): float            { return $this->subtotal; }
    public function getTaxAmount(): float           { return $this->taxAmount; }
    public function getTotal(): float               { return $this->total; }

    /** @return SaleDetail[] */
    public function getSaleDetails(): array { return $this->saleDetails; }

    /**
     * Replica CK_Sales_Total_Calculation: Total = Subtotal + TaxAmount.
     */
    public function calculateExpectedTotal(): float
    {
        return round($this->subtotal + $this->taxAmount, 2);
    }

    public function isTotalMathematicallyConsistent(): bool
    {
        return abs($this->total - $this->calculateExpectedTotal()) < 0.01;
    }
}
