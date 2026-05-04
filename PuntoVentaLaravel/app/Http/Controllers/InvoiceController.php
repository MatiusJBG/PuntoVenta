<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Application\UseCases\Invoice\GenerateInvoiceDocumentUseCase;
use DomainException;
use Illuminate\Http\Response;
use Infrastructure\Services\DocumentGenerator\PdfInvoiceRenderer;
use Infrastructure\Services\DocumentGenerator\XmlInvoiceRenderer;

/**
 * Despachador puro de facturas.
 * No contiene lógica de negocio ni de renderizado.
 * Solo orquesta: usa el UseCase para obtener el InvoiceDocument
 * y delega el renderizado al servicio de infraestructura correspondiente.
 */
class InvoiceController extends Controller
{
    public function __construct(
        private readonly GenerateInvoiceDocumentUseCase $generateInvoiceDocumentUseCase,
        private readonly PdfInvoiceRenderer             $pdfInvoiceRenderer,
        private readonly XmlInvoiceRenderer             $xmlInvoiceRenderer,
    ) {}

    /**
     * Descarga la factura en formato PDF.
     */
    public function downloadPdf(int $saleId): Response
    {
        return $this->streamDocumentToClient(
            saleId:   $saleId,
            renderer: $this->pdfInvoiceRenderer,
        );
    }

    /**
     * Descarga la factura en formato XML (estructura SRI Ecuador).
     */
    public function downloadXml(int $saleId): Response
    {
        return $this->streamDocumentToClient(
            saleId:   $saleId,
            renderer: $this->xmlInvoiceRenderer,
        );
    }

    // ── Private dispatcher ────────────────────────────────────────────────────

    /**
     * Ensambla el InvoiceDocument, lo renderiza y lo envía como descarga.
     */
    private function streamDocumentToClient(int $saleId, mixed $renderer): Response
    {
        try {
            $invoiceDocument = $this->generateInvoiceDocumentUseCase->execute($saleId);
            $documentContent = $renderer->render($invoiceDocument);

            $fileName = sprintf(
                'factura-%s.%s',
                $invoiceDocument->invoiceNumber,
                $renderer->getFileExtension()
            );

            return response($documentContent, 200, [
                'Content-Type'        => $renderer->getMimeType(),
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
                'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            ]);
        } catch (DomainException $domainException) {
            abort(404, $domainException->getMessage());
        }
    }
}
