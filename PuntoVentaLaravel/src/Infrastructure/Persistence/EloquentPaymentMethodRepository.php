<?php

declare(strict_types=1);

namespace Infrastructure\Persistence;

use Domain\Repositories\PaymentMethodRepositoryInterface;
use DomainException;
use Infrastructure\Persistence\Eloquent\PaymentMethodModel;

class EloquentPaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    public function findNameById(int $paymentMethodId): string
    {
        $paymentMethodModel = PaymentMethodModel::find($paymentMethodId);

        if ($paymentMethodModel === null) {
            throw new DomainException(
                "No se encontró ningún método de pago con el ID {$paymentMethodId}."
            );
        }

        return (string) $paymentMethodModel->Name;
    }
}
