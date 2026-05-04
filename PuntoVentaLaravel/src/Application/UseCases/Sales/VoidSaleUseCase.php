<?php

declare(strict_types=1);

namespace Application\UseCases\Sales;

use Domain\Entities\Sale;
use Domain\Exceptions\SaleAlreadyVoidedException;
use Domain\Repositories\ProductRepositoryInterface;
use Domain\Repositories\SaleRepositoryInterface;
use DomainException;

/**
 * Caso de Uso: Anular una venta completada.
 *
 * Workflow:
 *   1. Find    — Busca la venta por su SaleId.
 *   2. Validate — Verifica que pueda anularse (estado y restricción temporal).
 *   3. Persist  — Delega la operación atómica al repositorio.
 */
class VoidSaleUseCase
{
    public function __construct(
        private readonly SaleRepositoryInterface    $saleRepository,
        private readonly ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(int $saleId): void
    {
        $saleToVoid = $this->findSaleOrFail($saleId);

        $this->ensureSaleIsEligibleForVoiding($saleToVoid);

        // La restauración de stock y el cambio de estado ocurren
        // de forma atómica en un único DB::transaction dentro del repositorio.
        $this->saleRepository->voidSaleWithStockRestoration($saleToVoid);
    }

    // ── Private validation methods (Responsabilidad Única) ────────────────────

    private function findSaleOrFail(int $saleId): Sale
    {
        $saleToVoid = $this->saleRepository->findById($saleId);

        if ($saleToVoid === null) {
            throw new DomainException(
                "No se encontró ninguna venta con el ID {$saleId}."
            );
        }

        return $saleToVoid;
    }

    private function ensureSaleIsEligibleForVoiding(Sale $saleToVoid): void
    {
        $this->ensureSaleIsNotAlreadyVoided($saleToVoid);
        $this->ensureSaleIsInCompletedStatus($saleToVoid);
        $this->ensureVoidingOccursWithinAllowedTimeFrame($saleToVoid);
    }

    /**
     * Valida que la venta no haya sido anulada previamente (StatusId = 0).
     */
    private function ensureSaleIsNotAlreadyVoided(Sale $saleToVoid): void
    {
        if ($saleToVoid->isVoided()) {
            throw new SaleAlreadyVoidedException($saleToVoid->getSaleId());
        }
    }

    /**
     * Valida que la venta esté en estado Completada (StatusId = 1).
     * Protege contra estados desconocidos futuros.
     */
    private function ensureSaleIsInCompletedStatus(Sale $saleToVoid): void
    {
        if (! $saleToVoid->isCompleted()) {
            throw new DomainException(
                "La venta #{$saleToVoid->getSaleId()} tiene un estado desconocido "
                . "(StatusId={$saleToVoid->getStatusId()}) y no puede anularse."
            );
        }
    }

    /**
     * Restricción temporal: solo se pueden anular ventas del mismo día calendario.
     * Simula el cierre de caja diario.
     *
     * Para desactivar esta restricción en entornos de prueba, eliminarse este método
     * o hacerla configurable vía config('sales.allow_void_previous_days').
     */
    private function ensureVoidingOccursWithinAllowedTimeFrame(Sale $saleToVoid): void
    {
        if (! $saleToVoid->wasRegisteredOnSameCalendarDay()) {
            throw new DomainException(
                "La venta #{$saleToVoid->getSaleId()} no puede anularse porque fue registrada "
                . "en una fecha anterior. Solo se permiten anulaciones del día en curso."
            );
        }
    }
}
