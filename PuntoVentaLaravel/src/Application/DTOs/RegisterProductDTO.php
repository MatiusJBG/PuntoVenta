<?php

declare(strict_types=1);

namespace Application\DTOs;

readonly class RegisterProductDTO
{
    public function __construct(
        public string $name,
        public float  $price,
        public int    $stock,
    ) {}
}
