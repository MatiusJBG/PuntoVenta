<?php

declare(strict_types=1);

namespace Application\DTOs;

use DateTimeImmutable;

/**
 * Objeto de datos completo que representa una factura lista para ser renderizada.
 * Es inmutable (readonly): una vez construido, no puede modificarse.
 * Tanto el PDF como el XML se generan a partir de este mismo objeto.
 */
readonly class InvoiceDocument
{
    /**
     * @param InvoiceLineItemDTO[] $lineItemDetails
     */
    public function __construct(
        // ── Identificación ────────────────────────────────────────────────────
        public int               $saleId,
        public string            $invoiceNumber,
        public DateTimeImmutable $emissionDateTime,

        // ── Datos de la Empresa ───────────────────────────────────────────────
        public string $companyName,
        public string $companyRuc,
        public string $companyAddress,
        public string $companyPhone,

        // ── Datos del Cliente ─────────────────────────────────────────────────
        public string  $customerFullName,
        public string  $customerDocumentNumber,
        public ?string $customerAddress,
        public ?string $customerPhone,
        public ?string $customerCity,

        // ── Ítems de la Factura ───────────────────────────────────────────────
        public array  $lineItemDetails,

        // ── Resumen Tributario ────────────────────────────────────────────────
        public float  $subtotalAmount,
        public float  $taxRate,
        public float  $taxAmount,
        public float  $totalAmount,

        // ── Pago ──────────────────────────────────────────────────────────────
        public string $paymentMethodName,
    ) {}

    /**
     * Retorna la tasa de IVA formateada como porcentaje (ej. "15%").
     */
    public function getFormattedTaxRateLabel(): string
    {
        return number_format($this->taxRate * 100, 0) . '%';
    }
}
