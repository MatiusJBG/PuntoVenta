<?php

declare(strict_types=1);

namespace Application\UseCases\Invoice;

use Application\DTOs\InvoiceDocument;
use Application\DTOs\InvoiceLineItemDTO;
use Domain\Entities\Customer;
use Domain\Entities\Sale;
use Domain\Entities\SaleDetail;
use Domain\Repositories\CustomerRepositoryInterface;
use Domain\Repositories\PaymentMethodRepositoryInterface;
use Domain\Repositories\ProductRepositoryInterface;
use Domain\Repositories\SaleRepositoryInterface;
use DomainException;

/**
 * Caso de Uso: Ensamblar el documento de factura a partir de los datos de la BD.
 *
 * Workflow:
 *   1. Find       — Carga la venta con sus detalles.
 *   2. Aggregate  — Carga el cliente, los productos y el método de pago.
 *   3. Assemble   — Construye el InvoiceDocument inmutable.
 */
class GenerateInvoiceDocumentUseCase
{
    public function __construct(
        private readonly SaleRepositoryInterface          $saleRepository,
        private readonly CustomerRepositoryInterface      $customerRepository,
        private readonly ProductRepositoryInterface       $productRepository,
        private readonly PaymentMethodRepositoryInterface $paymentMethodRepository,
        private readonly float                            $taxRate,
        private readonly array                            $companyInfo,
    ) {}

    public function execute(int $saleId): InvoiceDocument
    {
        $saleEntity     = $this->findSaleOrFail($saleId);
        $customerEntity = $this->findCustomerOrFail($saleEntity->getCustomerId());

        // Variables nombradas según el estándar del Master Prompt
        $invoiceHeader        = $this->buildInvoiceHeader($saleEntity);
        $lineItemDetails      = $this->buildLineItemDetails($saleEntity->getSaleDetails());
        $taxCalculationSummary = $this->buildTaxCalculationSummary($saleEntity);

        $paymentMethodName = $this->paymentMethodRepository
            ->findNameById($saleEntity->getPaymentMethodId());

        return new InvoiceDocument(
            saleId:                $saleEntity->getSaleId(),
            invoiceNumber:         $invoiceHeader['number'],
            emissionDateTime:      $saleEntity->getSaleDate(),
            companyName:           $this->companyInfo['name'],
            companyRuc:            $this->companyInfo['ruc'],
            companyAddress:        $this->companyInfo['address'],
            companyPhone:          $this->companyInfo['phone'],
            customerFullName:      $customerEntity->getFullName(),
            customerDocumentNumber: $customerEntity->getDocumentNumber(),
            customerAddress:       $customerEntity->getAddress(),
            customerPhone:         $customerEntity->getPhone(),
            customerCity:          $customerEntity->getCity(),
            lineItemDetails:       $lineItemDetails,
            subtotalAmount:        $taxCalculationSummary['subtotal'],
            taxRate:               $taxCalculationSummary['taxRate'],
            taxAmount:             $taxCalculationSummary['taxAmount'],
            totalAmount:           $taxCalculationSummary['total'],
            paymentMethodName:     $paymentMethodName,
        );
    }

    // ── Private assembly methods ──────────────────────────────────────────────

    private function findSaleOrFail(int $saleId): Sale
    {
        $saleEntity = $this->saleRepository->findById($saleId);

        if ($saleEntity === null) {
            throw new DomainException("No existe ninguna venta con el ID {$saleId}.");
        }

        return $saleEntity;
    }

    private function findCustomerOrFail(int $customerId): Customer
    {
        $customerEntity = $this->customerRepository->findById($customerId);

        if ($customerEntity === null) {
            throw new DomainException("No existe ningún cliente con el ID {$customerId}.");
        }

        return $customerEntity;
    }

    /** @return array{number: string} */
    private function buildInvoiceHeader(Sale $saleEntity): array
    {
        return [
            'number' => $this->formatInvoiceNumber($saleEntity->getSaleId()),
        ];
    }

    /**
     * Formatea el SaleId con la máscara legal ecuatoriana: 001-001-000000000.
     */
    private function formatInvoiceNumber(int $saleId): string
    {
        return '001-001-' . str_pad((string) $saleId, 9, '0', STR_PAD_LEFT);
    }

    /**
     * @param  SaleDetail[]      $saleDetails
     * @return InvoiceLineItemDTO[]
     */
    private function buildLineItemDetails(array $saleDetails): array
    {
        $lineItemDetails = [];

        foreach ($saleDetails as $saleDetailItem) {
            $productEntity = $this->productRepository->findById($saleDetailItem->getProductId());

            $lineItemDetails[] = new InvoiceLineItemDTO(
                productCode:        $saleDetailItem->getProductId(),
                productDescription: $productEntity?->getName() ?? 'Producto desconocido',
                quantity:           $saleDetailItem->getQuantity(),
                unitPrice:          $saleDetailItem->getUnitPrice(),
                lineSubtotal:       $saleDetailItem->calculateLineSubtotal(),
            );
        }

        return $lineItemDetails;
    }

    /**
     * @return array{subtotal: float, taxRate: float, taxAmount: float, total: float}
     */
    private function buildTaxCalculationSummary(Sale $saleEntity): array
    {
        return [
            'subtotal' => $saleEntity->getSubtotal(),
            'taxRate'  => $this->taxRate,
            'taxAmount' => $saleEntity->getTaxAmount(),
            'total'    => $saleEntity->getTotal(),
        ];
    }
}
