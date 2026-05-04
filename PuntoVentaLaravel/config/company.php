<?php

declare(strict_types=1);

/**
 * Datos de la empresa emisora de facturas.
 * Editar estas variables de entorno en .env para personalizar el sistema.
 */
return [
    'name'    => env('COMPANY_NAME',    'TechStore Ecuador S.A.'),
    'ruc'     => env('COMPANY_RUC',     '1790012345001'),
    'address' => env('COMPANY_ADDRESS', 'Av. Naciones Unidas E3-25, Quito'),
    'phone'   => env('COMPANY_PHONE',   '02-2987654'),
];
