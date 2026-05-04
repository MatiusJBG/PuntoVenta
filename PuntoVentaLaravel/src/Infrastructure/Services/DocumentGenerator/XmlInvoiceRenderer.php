<?php

declare(strict_types=1);

namespace Infrastructure\Services\DocumentGenerator;

use Application\DTOs\InvoiceDocument;
use Application\DTOs\InvoiceLineItemDTO;
use Domain\Services\InvoiceRendererInterface;
use DOMDocument;
use DOMElement;

/**
 * Implementación del renderizador de XML usando DOMDocument (estándar PHP).
 * Genera un XML con estructura compatible con SRI Ecuador (facturación electrónica).
 * Escapado automático de caracteres especiales (tildes, ñ, etc.).
 */
class XmlInvoiceRenderer implements InvoiceRendererInterface
{
    public function render(InvoiceDocument $invoiceDocument): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $facturaNode = $dom->createElement('factura');
        $facturaNode->setAttribute('id', 'comprobante');
        $facturaNode->setAttribute('version', '1.1.0');
        $dom->appendChild($facturaNode);

        $facturaNode->appendChild($this->buildTaxInformationNode($dom, $invoiceDocument));
        $facturaNode->appendChild($this->buildInvoiceInformationNode($dom, $invoiceDocument));
        $facturaNode->appendChild($this->buildDetailsNode($dom, $invoiceDocument));
        $facturaNode->appendChild($this->buildTotalsNode($dom, $invoiceDocument));

        return $dom->saveXML();
    }

    public function getMimeType(): string
    {
        return 'application/xml';
    }

    public function getFileExtension(): string
    {
        return 'xml';
    }

    // ── Private builder methods ───────────────────────────────────────────────

    private function buildTaxInformationNode(DOMDocument $dom, InvoiceDocument $invoiceDocument): DOMElement
    {
        $infoTributaria = $dom->createElement('infoTributaria');

        $this->appendTextChild($dom, $infoTributaria, 'ambiente',       '1');
        $this->appendTextChild($dom, $infoTributaria, 'tipoEmision',    '1');
        $this->appendTextChild($dom, $infoTributaria, 'razonSocial',    $invoiceDocument->companyName);
        $this->appendTextChild($dom, $infoTributaria, 'ruc',            $invoiceDocument->companyRuc);
        $this->appendTextChild($dom, $infoTributaria, 'codDoc',         '01');
        $this->appendTextChild($dom, $infoTributaria, 'estab',          '001');
        $this->appendTextChild($dom, $infoTributaria, 'ptoEmi',         '001');
        $this->appendTextChild($dom, $infoTributaria, 'secuencial',     str_pad((string) $invoiceDocument->saleId, 9, '0', STR_PAD_LEFT));
        $this->appendTextChild($dom, $infoTributaria, 'dirMatriz',      $invoiceDocument->companyAddress);

        return $infoTributaria;
    }

    private function buildInvoiceInformationNode(DOMDocument $dom, InvoiceDocument $invoiceDocument): DOMElement
    {
        $infoFactura = $dom->createElement('infoFactura');

        $this->appendTextChild($dom, $infoFactura, 'fechaEmision',                $invoiceDocument->emissionDateTime->format('d/m/Y'));
        $this->appendTextChild($dom, $infoFactura, 'dirEstablecimiento',           $invoiceDocument->companyAddress);
        $this->appendTextChild($dom, $infoFactura, 'tipoIdentificacionComprador',  '05');
        $this->appendTextChild($dom, $infoFactura, 'razonSocialComprador',         $invoiceDocument->customerFullName);
        $this->appendTextChild($dom, $infoFactura, 'identificacionComprador',      $invoiceDocument->customerDocumentNumber);
        $this->appendTextChild($dom, $infoFactura, 'direccionComprador',           $invoiceDocument->customerAddress ?? '');
        $this->appendTextChild($dom, $infoFactura, 'totalSinImpuestos',            number_format($invoiceDocument->subtotalAmount, 2, '.', ''));
        $this->appendTextChild($dom, $infoFactura, 'totalDescuento',               '0.00');

        $totalConImpuestos = $dom->createElement('totalConImpuestos');
        $totalImpuesto     = $dom->createElement('totalImpuesto');
        $this->appendTextChild($dom, $totalImpuesto, 'codigo',            '2');
        $this->appendTextChild($dom, $totalImpuesto, 'codigoPorcentaje',  $invoiceDocument->getFormattedTaxRateLabel());
        $this->appendTextChild($dom, $totalImpuesto, 'baseImponible',     number_format($invoiceDocument->subtotalAmount, 2, '.', ''));
        $this->appendTextChild($dom, $totalImpuesto, 'valor',             number_format($invoiceDocument->taxAmount, 2, '.', ''));
        $totalConImpuestos->appendChild($totalImpuesto);
        $infoFactura->appendChild($totalConImpuestos);

        $this->appendTextChild($dom, $infoFactura, 'propina',       '0.00');
        $this->appendTextChild($dom, $infoFactura, 'importeTotal',  number_format($invoiceDocument->totalAmount, 2, '.', ''));
        $this->appendTextChild($dom, $infoFactura, 'moneda',        'DOLAR');
        $this->appendTextChild($dom, $infoFactura, 'formaPago',     $invoiceDocument->paymentMethodName);

        return $infoFactura;
    }

    private function buildDetailsNode(DOMDocument $dom, InvoiceDocument $invoiceDocument): DOMElement
    {
        $detalles = $dom->createElement('detalles');

        foreach ($invoiceDocument->lineItemDetails as $lineItem) {
            $detalles->appendChild($this->buildSingleDetailNode($dom, $lineItem));
        }

        return $detalles;
    }

    private function buildSingleDetailNode(DOMDocument $dom, InvoiceLineItemDTO $lineItem): DOMElement
    {
        $detalle = $dom->createElement('detalle');

        $this->appendTextChild($dom, $detalle, 'codigoPrincipal', (string) $lineItem->productCode);
        $this->appendTextChild($dom, $detalle, 'descripcion',     $lineItem->productDescription);
        $this->appendTextChild($dom, $detalle, 'cantidad',        (string) $lineItem->quantity);
        $this->appendTextChild($dom, $detalle, 'precioUnitario',  number_format($lineItem->unitPrice, 2, '.', ''));
        $this->appendTextChild($dom, $detalle, 'descuento',       '0.00');
        $this->appendTextChild($dom, $detalle, 'precioTotalSinImpuesto', number_format($lineItem->lineSubtotal, 2, '.', ''));

        return $detalle;
    }

    private function buildTotalsNode(DOMDocument $dom, InvoiceDocument $invoiceDocument): DOMElement
    {
        $totales = $dom->createElement('totales');

        $this->appendTextChild($dom, $totales, 'subtotal0',        '0.00');
        $this->appendTextChild($dom, $totales, 'subtotalIVA',      number_format($invoiceDocument->subtotalAmount, 2, '.', ''));
        $this->appendTextChild($dom, $totales, 'valorIVA',         number_format($invoiceDocument->taxAmount, 2, '.', ''));
        $this->appendTextChild($dom, $totales, 'totalFactura',     number_format($invoiceDocument->totalAmount, 2, '.', ''));

        return $totales;
    }

    /**
     * Helper: crea un nodo texto. DOMDocument escapa automáticamente
     * caracteres especiales (tildes, ñ, &, <, >).
     */
    private function appendTextChild(DOMDocument $dom, DOMElement $parent, string $tagName, string $value): void
    {
        $node = $dom->createElement($tagName);
        $node->appendChild($dom->createTextNode($value));
        $parent->appendChild($node);
    }
}
