<?php

declare(strict_types=1);

namespace App\Providers;

use Application\UseCases\RegisterSaleUseCase;
use Domain\Repositories\CustomerRepositoryInterface;
use Domain\Repositories\ProductRepositoryInterface;
use Domain\Repositories\SaleRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Persistence\EloquentCustomerRepository;
use Infrastructure\Persistence\EloquentProductRepository;
use Infrastructure\Persistence\EloquentSaleRepository;

class DependencyInjectionServiceProvider extends ServiceProvider
{
    /**
     * Registra los bindings del contenedor de inversión de dependencias.
     * Aquí se decide QUÉ implementación concreta satisface cada interfaz del dominio.
     */
    public function register(): void
    {
        // Binding: Interface → Implementación concreta (Regla D de SOLID)
        $this->app->bind(
            CustomerRepositoryInterface::class,
            EloquentCustomerRepository::class
        );

        $this->app->bind(
            ProductRepositoryInterface::class,
            EloquentProductRepository::class
        );

        $this->app->bind(
            SaleRepositoryInterface::class,
            EloquentSaleRepository::class
        );

        // RegisterSaleUseCase requiere el taxRate como argumento escalar.
        // Se lee desde config/tax.php para mantener el UseCase agnóstico a Laravel.
        $this->app->bind(RegisterSaleUseCase::class, function ($app): RegisterSaleUseCase {
            return new RegisterSaleUseCase(
                saleRepository:     $app->make(SaleRepositoryInterface::class),
                productRepository:  $app->make(ProductRepositoryInterface::class),
                customerRepository: $app->make(CustomerRepositoryInterface::class),
                taxRate:            (float) config('tax.rate'),
            );
        });
    }

    public function boot(): void {}
}
