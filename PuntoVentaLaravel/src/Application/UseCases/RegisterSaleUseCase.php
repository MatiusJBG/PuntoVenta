<?php

declare(strict_types=1);

namespace Application\UseCases;

use Application\DTOs\RegisterSaleDTO;
use Application\DTOs\SaleItemDTO;
use DateTimeImmutable;
use Domain\Entities\Product;
use Domain\Entities\Sale;
use Domain\Entities\SaleDetail;
use Domain\Repositories\CustomerRepositoryInterface;
use Domain\Repositories\ProductRepositoryInterface;
use Domain\Repositories\SaleRepositoryInterface;
use DomainException;

class RegisterSaleUseCase
{
    private const DEFAULT_SALE_STATUS_COMPLETED = 1;

    public function __construct(
        private readonly SaleRepositoryInterface     $saleRepository,
        private readonly ProductRepositoryInterface  $productRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly float                       $taxRate,
    ) {}

    public function execute(RegisterSaleDTO $registerSaleDTO): Sale
    {
        $this->ensureCustomerExists($registerSaleDTO->customerId);
        $this->ensureSaleHasAtLeastOneItem($registerSaleDTO->saleItems);

        $loadedProductEntities = $this->loadAndValidateProductsForSale($registerSaleDTO->saleItems);

        $nextSaleId = $this->saleRepository->nextId();

        $saleDetailEntities = $this->buildSaleDetailEntities(
            saleId:    $nextSaleId,
            saleItems: $registerSaleDTO->saleItems,
        );

        $subtotalAmount = $this->calculateSaleSubtotal($saleDetailEntities);
        $taxAmount      = $this->calculateTaxAmountForSale($subtotalAmount);
        $totalAmount    = $this->calculateFinalTotal($subtotalAmount, $taxAmount);

        // PHP 8.2 no permite spread posicional después de named arguments.
        // Se llama al constructor con argumentos posicionales para habilitar el spread variádico.
        $saleEntity = new Sale(
            $nextSaleId,
            $registerSaleDTO->customerId,
            new DateTimeImmutable(),
            $registerSaleDTO->paymentMethodId,
            self::DEFAULT_SALE_STATUS_COMPLETED,
            $subtotalAmount,
            $taxAmount,
            $totalAmount,
            ...$saleDetailEntities,
        );

        $this->ensureSaleTotalIsMathematicallyConsistent($saleEntity);

        $persistedSaleEntity = $this->saleRepository->save($saleEntity);

        $this->decreaseStockForAllSoldProducts($registerSaleDTO->saleItems, $loadedProductEntities);

        return $persistedSaleEntity;
    }

    private function ensureCustomerExists(int $customerId): void
    {
        if ($this->customerRepository->findById($customerId) === null) {
            throw new DomainException(
                "No se encontró ningún cliente con el ID {$customerId}."
            );
        }
    }

    private function ensureSaleHasAtLeastOneItem(array $saleItems): void
    {
        if (empty($saleItems)) {
            throw new DomainException("La venta debe contener al menos un producto.");
        }
    }

    /**
     * @param  SaleItemDTO[] $saleItems
     * @return Product[]  Indexado por productId
     */
    private function loadAndValidateProductsForSale(array $saleItems): array
    {
        $loadedProducts = [];

        foreach ($saleItems as $saleItemDTO) {
            $productEntity = $this->productRepository->findById($saleItemDTO->productId);

            if ($productEntity === null) {
                throw new DomainException(
                    "No se encontró ningún producto con el ID {$saleItemDTO->productId}."
                );
            }

            if (! $productEntity->hasEnoughStock($saleItemDTO->quantity)) {
                throw new DomainException(
                    "Stock insuficiente para '{$productEntity->getName()}'. "
                    . "Disponible: {$productEntity->getStock()}, solicitado: {$saleItemDTO->quantity}."
                );
            }

            $loadedProducts[$saleItemDTO->productId] = $productEntity;
        }

        return $loadedProducts;
    }

    /**
     * @param  SaleItemDTO[] $saleItems
     * @return SaleDetail[]
     */
    private function buildSaleDetailEntities(int $saleId, array $saleItems): array
    {
        $saleDetailEntities = [];
        $nextDetailId       = $this->saleRepository->nextDetailId();

        foreach ($saleItems as $index => $saleItemDTO) {
            $saleDetailEntities[] = new SaleDetail(
                saleDetailId: $nextDetailId + $index,
                saleId:       $saleId,
                productId:    $saleItemDTO->productId,
                quantity:     $saleItemDTO->quantity,
                unitPrice:    $saleItemDTO->unitPrice,
            );
        }

        return $saleDetailEntities;
    }

    /** @param SaleDetail[] $saleDetailEntities */
    private function calculateSaleSubtotal(array $saleDetailEntities): float
    {
        $subtotalAmount = 0.0;

        foreach ($saleDetailEntities as $saleDetailEntity) {
            $subtotalAmount += $saleDetailEntity->calculateLineSubtotal();
        }

        return round($subtotalAmount, 2);
    }

    private function calculateTaxAmountForSale(float $subtotalAmount): float
    {
        return round($subtotalAmount * $this->taxRate, 2);
    }

    private function calculateFinalTotal(float $subtotalAmount, float $taxAmount): float
    {
        return round($subtotalAmount + $taxAmount, 2);
    }

    private function ensureSaleTotalIsMathematicallyConsistent(Sale $saleEntity): void
    {
        if (! $saleEntity->isTotalMathematicallyConsistent()) {
            throw new DomainException(
                "El total de la venta no es consistente. "
                . "Esperado: {$saleEntity->calculateExpectedTotal()}, obtenido: {$saleEntity->getTotal()}."
            );
        }
    }

    /**
     * @param SaleItemDTO[] $saleItems
     * @param Product[]     $loadedProductEntities
     */
    private function decreaseStockForAllSoldProducts(array $saleItems, array $loadedProductEntities): void
    {
        foreach ($saleItems as $saleItemDTO) {
            $productEntity = $loadedProductEntities[$saleItemDTO->productId];
            $productEntity->decreaseStock($saleItemDTO->quantity);
            $this->productRepository->updateStock($saleItemDTO->productId, $productEntity->getStock());
        }
    }
}
