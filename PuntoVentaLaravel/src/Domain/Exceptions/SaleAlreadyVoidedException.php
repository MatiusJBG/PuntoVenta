<?php

declare(strict_types=1);

namespace Domain\Exceptions;

use DomainException;

/**
 * Se lanza cuando se intenta anular una venta que ya tiene StatusId = 0 (Anulada).
 */
class SaleAlreadyVoidedException extends DomainException
{
    public function __construct(int $saleId)
    {
        parent::__construct(
            "La venta #{$saleId} ya fue anulada anteriormente y no puede procesarse nuevamente."
        );
    }
}
