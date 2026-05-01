<?php

declare(strict_types=1);

namespace Domain\Validation;

use DomainException;

/**
 * Valida el DocumentNumber según las reglas ecuatorianas.
 * Replica la constraint CK_Customers_DocumentNumber_Ecuador de MySQL.
 */
class EcuadorianDocumentValidator
{
    private const CONSUMIDOR_FINAL_DOCUMENT = '9999999999';
    private const REQUIRED_DIGIT_LENGTH     = 10;

    public function validate(string $documentNumber): void
    {
        if ($documentNumber === self::CONSUMIDOR_FINAL_DOCUMENT) {
            return;
        }

        if (! preg_match('/^[0-9]+$/', $documentNumber)) {
            throw new DomainException(
                "El número de documento '{$documentNumber}' debe contener únicamente dígitos numéricos."
            );
        }

        if (strlen($documentNumber) !== self::REQUIRED_DIGIT_LENGTH) {
            throw new DomainException(
                "El número de documento '{$documentNumber}' debe tener exactamente "
                . self::REQUIRED_DIGIT_LENGTH . " dígitos (Cédula ecuatoriana)."
            );
        }
    }

    public function isValid(string $documentNumber): bool
    {
        try {
            $this->validate($documentNumber);
            return true;
        } catch (DomainException) {
            return false;
        }
    }
}
