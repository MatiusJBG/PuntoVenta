<?php

declare(strict_types=1);

namespace Infrastructure\Services\DocumentGenerator;

use Application\DTOs\InvoiceDocument;
use Domain\Services\InvoiceRendererInterface;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Implementación del renderizador de PDF usando DomPDF.
 * Cumple DIP: implementa InvoiceRendererInterface sin que el consumidor
 * sepa que internamente se usa la librería DomPDF.
 */
class PdfInvoiceRenderer implements InvoiceRendererInterface
{
    public function render(InvoiceDocument $invoiceDocument): string
    {
        $pdf = Pdf::loadView('invoice.pdf', ['invoiceDocument' => $invoiceDocument])
            ->setPaper('A4', 'portrait');

        return $pdf->output();
    }

    public function getMimeType(): string
    {
        return 'application/pdf';
    }

    public function getFileExtension(): string
    {
        return 'pdf';
    }
}
