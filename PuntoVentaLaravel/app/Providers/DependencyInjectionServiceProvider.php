<?php

declare(strict_types=1);

namespace App\Providers;

use Application\UseCases\Invoice\GenerateInvoiceDocumentUseCase;
use Application\UseCases\RegisterSaleUseCase;
use Domain\Repositories\CustomerRepositoryInterface;
use Domain\Repositories\PaymentMethodRepositoryInterface;
use Domain\Repositories\ProductRepositoryInterface;
use Domain\Repositories\SaleRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Persistence\EloquentCustomerRepository;
use Infrastructure\Persistence\EloquentPaymentMethodRepository;
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
        // ── Repository Bindings (Interface → Implementación Eloquent) ─────────

        $this->app->bind(CustomerRepositoryInterface::class,      EloquentCustomerRepository::class);
        $this->app->bind(ProductRepositoryInterface::class,       EloquentProductRepository::class);
        $this->app->bind(SaleRepositoryInterface::class,          EloquentSaleRepository::class);
        $this->app->bind(PaymentMethodRepositoryInterface::class, EloquentPaymentMethodRepository::class);

        // ── Use Cases con dependencias escalares ──────────────────────────────

        // RegisterSaleUseCase: requiere taxRate escalar desde config/tax.php
        $this->app->bind(RegisterSaleUseCase::class, function ($app): RegisterSaleUseCase {
            return new RegisterSaleUseCase(
                saleRepository:     $app->make(SaleRepositoryInterface::class),
                productRepository:  $app->make(ProductRepositoryInterface::class),
                customerRepository: $app->make(CustomerRepositoryInterface::class),
                taxRate:            (float) config('tax.rate'),
            );
        });

        // GenerateInvoiceDocumentUseCase: requiere taxRate y companyInfo escalares
        $this->app->bind(GenerateInvoiceDocumentUseCase::class, function ($app): GenerateInvoiceDocumentUseCase {
            return new GenerateInvoiceDocumentUseCase(
                saleRepository:          $app->make(SaleRepositoryInterface::class),
                customerRepository:      $app->make(CustomerRepositoryInterface::class),
                productRepository:       $app->make(ProductRepositoryInterface::class),
                paymentMethodRepository: $app->make(PaymentMethodRepositoryInterface::class),
                taxRate:                 (float) config('tax.rate'),
                companyInfo:             (array)  config('company'),
            );
        });
    }

    public function boot(): void {}
}
