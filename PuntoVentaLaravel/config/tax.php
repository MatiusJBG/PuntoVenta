<?php

declare(strict_types=1);

/**
 * Configuración del Impuesto al Valor Agregado (IVA).
 *
 * Para cambiar la tasa en el futuro, basta con actualizar la variable
 * de entorno TAX_RATE en el archivo .env del servidor.
 *
 * Ejemplo: TAX_RATE=0.12 para el 12%
 */
return [
    'rate' => (float) env('TAX_RATE', 0.15),
];
