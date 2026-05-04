<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

// Página principal → redirige al listado de ventas
Route::get('/', fn () => redirect()->route('sales.index'));

// Clientes
Route::get('/customers',        [CustomerController::class, 'index'])->name('customers.index');
Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
Route::post('/customers',       [CustomerController::class, 'store'])->name('customers.store');

// Productos
Route::get('/products',        [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products',       [ProductController::class, 'store'])->name('products.store');

// Ventas
Route::get('/sales',        [SaleController::class, 'index'])->name('sales.index');
Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
Route::post('/sales',       [SaleController::class, 'store'])->name('sales.store');

// Anulación de ventas — POST con formulario HTML (sin necesidad de JavaScript)
Route::post('/sales/{saleId}/void', [SaleController::class, 'void'])->name('sales.void');

// Facturación — Descarga de documentos legales
Route::get('/invoices/{saleId}/pdf', [\App\Http\Controllers\InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
Route::get('/invoices/{saleId}/xml', [\App\Http\Controllers\InvoiceController::class, 'downloadXml'])->name('invoices.xml');

