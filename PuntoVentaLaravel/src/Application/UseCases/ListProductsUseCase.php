<?php

declare(strict_types=1);

namespace Application\UseCases;

use Domain\Entities\Product;
use Domain\Repositories\ProductRepositoryInterface;

class ListProductsUseCase
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    /** @return Product[] */
    public function execute(): array
    {
        return $this->productRepository->findAll();
    }
}
