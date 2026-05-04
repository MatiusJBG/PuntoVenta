<?php

declare(strict_types=1);

namespace Domain\Services;

use Application\DTOs\InvoiceDocument;

/**
 * Contrato para cualquier implementación de renderizado de facturas.
 * Cumple Dependency Inversion: el controlador depende de esta abstracción,
 * nunca de DomPDF, SimpleXML u otra librería concreta.
 */
interface InvoiceRendererInterface
{
    /**
     * Renderiza el documento de factura y retorna el contenido binario/texto.
     */
    public function render(InvoiceDocument $invoiceDocument): string;

    public function getMimeType(): string;

    public function getFileExtension(): string;
}
