<?php

declare(strict_types=1);

namespace Infrastructure\Persistence;

use DateTimeImmutable;
use Domain\Entities\Sale;
use Domain\Entities\SaleDetail;
use Domain\Repositories\SaleRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Infrastructure\Persistence\Eloquent\SaleDetailModel;
use Infrastructure\Persistence\Eloquent\SaleModel;

class EloquentSaleRepository implements SaleRepositoryInterface
{
    public function save(Sale $saleEntity): Sale
    {
        DB::transaction(function () use ($saleEntity): void {
            SaleModel::create([
                'SaleId'          => $saleEntity->getSaleId(),
                'CustomerId'      => $saleEntity->getCustomerId(),
                'SaleDate'        => $saleEntity->getSaleDate()->format('Y-m-d H:i:s'),
                'PaymentMethodId' => $saleEntity->getPaymentMethodId(),
                'StatusId'        => $saleEntity->getStatusId(),
                'Subtotal'        => $saleEntity->getSubtotal(),
                'TaxAmount'       => $saleEntity->getTaxAmount(),
                'Total'           => $saleEntity->getTotal(),
            ]);

            foreach ($saleEntity->getSaleDetails() as $saleDetailEntity) {
                SaleDetailModel::create([
                    'SaleDetailId' => $saleDetailEntity->getSaleDetailId(),
                    'SaleId'       => $saleDetailEntity->getSaleId(),
                    'ProductId'    => $saleDetailEntity->getProductId(),
                    'Quantity'     => $saleDetailEntity->getQuantity(),
                    'UnitPrice'    => $saleDetailEntity->getUnitPrice(),
                    // 'Subtotal' es GENERATED ALWAYS AS STORED — MySQL lo calcula
                ]);
            }
        });

        return $saleEntity;
    }

    public function findById(int $saleId): ?Sale
    {
        $saleModel = SaleModel::with('saleDetails')->find($saleId);

        return $saleModel ? $this->mapModelToEntity($saleModel) : null;
    }

    /** @return Sale[] */
    public function findAll(): array
    {
        return SaleModel::with(['saleDetails', 'customer'])
            ->orderByDesc('SaleDate')
            ->get()
            ->map(fn (SaleModel $model) => $this->mapModelToEntity($model))
            ->all();
    }

    public function nextId(): int
    {
        $maximumCurrentId = SaleModel::max('SaleId');

        return $maximumCurrentId !== null ? (int) $maximumCurrentId + 1 : 1;
    }

    public function nextDetailId(): int
    {
        $maximumCurrentId = SaleDetailModel::max('SaleDetailId');

        return $maximumCurrentId !== null ? (int) $maximumCurrentId + 1 : 1;
    }

    private function mapModelToEntity(SaleModel $saleModel): Sale
    {
        $saleDetailEntities = $saleModel->saleDetails
            ->map(fn (SaleDetailModel $detailModel) => new SaleDetail(
                saleDetailId: (int) $detailModel->SaleDetailId,
                saleId:       (int) $detailModel->SaleId,
                productId:    (int) $detailModel->ProductId,
                quantity:     (int) $detailModel->Quantity,
                unitPrice:    (float) $detailModel->UnitPrice,
            ))
            ->all();

        // PHP 8.2 no permite unpacking posicional después de named arguments.
        // Se invocan los argumentos en orden posicional para permitir el spread variádico.
        return new Sale(
            (int) $saleModel->SaleId,
            (int) $saleModel->CustomerId,
            DateTimeImmutable::createFromInterface($saleModel->SaleDate),
            (int) $saleModel->PaymentMethodId,
            (int) $saleModel->StatusId,
            (float) $saleModel->Subtotal,
            (float) $saleModel->TaxAmount,
            (float) $saleModel->Total,
            ...$saleDetailEntities,
        );
    }
}
