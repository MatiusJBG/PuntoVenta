<?php

declare(strict_types=1);

namespace Domain\Validation;

use DomainException;

/**
 * Valida el número de teléfono ecuatoriano.
 * Replica la constraint CK_Customers_Phone_Ecuador de MySQL.
 */
class PhoneValidator
{
    private const MINIMUM_DIGIT_LENGTH = 7;
    private const MAXIMUM_DIGIT_LENGTH = 10;

    public function validate(?string $phoneNumber): void
    {
        if ($phoneNumber === null) {
            return;
        }

        if (! preg_match('/^[0-9]+$/', $phoneNumber)) {
            throw new DomainException(
                "El teléfono '{$phoneNumber}' debe contener únicamente dígitos numéricos."
            );
        }

        $phoneLength = strlen($phoneNumber);

        if ($phoneLength < self::MINIMUM_DIGIT_LENGTH || $phoneLength > self::MAXIMUM_DIGIT_LENGTH) {
            throw new DomainException(
                "El teléfono '{$phoneNumber}' debe tener entre "
                . self::MINIMUM_DIGIT_LENGTH . " y " . self::MAXIMUM_DIGIT_LENGTH . " dígitos."
            );
        }
    }
}
