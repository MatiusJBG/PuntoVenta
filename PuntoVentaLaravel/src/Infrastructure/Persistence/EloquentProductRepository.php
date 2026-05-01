<?php

declare(strict_types=1);

namespace Infrastructure\Persistence;

use Domain\Entities\Product;
use Domain\Repositories\ProductRepositoryInterface;
use Infrastructure\Persistence\Eloquent\ProductModel;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function findById(int $productId): ?Product
    {
        $productModel = ProductModel::find($productId);

        return $productModel ? $this->mapModelToEntity($productModel) : null;
    }

    /** @return Product[] */
    public function findAll(): array
    {
        return ProductModel::orderBy('Name')
            ->get()
            ->map(fn (ProductModel $model) => $this->mapModelToEntity($model))
            ->all();
    }

    public function save(Product $productEntity): Product
    {
        ProductModel::create([
            'ProductId' => $productEntity->getProductId(),
            'Name'      => $productEntity->getName(),
            'Price'     => $productEntity->getPrice(),
            'Stock'     => $productEntity->getStock(),
        ]);

        return $productEntity;
    }

    public function updateStock(int $productId, int $newStock): void
    {
        ProductModel::where('ProductId', $productId)
            ->update(['Stock' => $newStock]);
    }

    public function nextId(): int
    {
        $maximumCurrentId = ProductModel::max('ProductId');

        return $maximumCurrentId !== null ? (int) $maximumCurrentId + 1 : 1;
    }

    private function mapModelToEntity(ProductModel $productModel): Product
    {
        return new Product(
            productId: (int) $productModel->ProductId,
            name:      (string) $productModel->Name,
            price:     (float) $productModel->Price,
            stock:     (int) $productModel->Stock,
        );
    }
}
