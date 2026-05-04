<?php

declare(strict_types=1);

namespace Domain\Repositories;

interface PaymentMethodRepositoryInterface
{
    /**
     * Retorna el nombre del método de pago dado su ID.
     * Lanza DomainException si el ID no existe en el catálogo.
     */
    public function findNameById(int $paymentMethodId): string;
}
