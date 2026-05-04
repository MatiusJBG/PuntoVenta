@extends('layouts.app')
@section('title', 'Productos')
@section('header_title', 'Catálogo de Productos')

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-base font-semibold leading-6 text-slate-900">Inventario</h2>
        <p class="mt-2 text-sm text-slate-600">Catálogo de productos disponibles para la venta.</p>
    </div>
    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <a href="{{ route('products.create') }}" 
           class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-200">
            <svg class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
            </svg>
            Nuevo Producto
        </a>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm ring-1 ring-slate-300 sm:rounded-xl bg-white">
                <table class="min-w-full divide-y divide-slate-300">
                    <thead class="bg-slate-50 sticky top-0 z-10">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-semibold text-slate-900 uppercase tracking-wide sm:pl-6">ID</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900 uppercase tracking-wide">Nombre / Descripción</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-xs font-semibold text-slate-900 uppercase tracking-wide">Precio</th>
                            <th scope="col" class="py-3.5 pl-3 pr-4 sm:pr-6 text-right text-xs font-semibold text-slate-900 uppercase tracking-wide">Stock Disponible</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($products as $product)
                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-slate-900 sm:pl-6">
                                {{ str_pad($product->getProductId(), 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-900 font-medium">
                                {{ $product->getName() }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600 text-right">
                                $ {{ number_format($product->getPrice(), 2) }}
                            </td>
                            <td class="whitespace-nowrap py-4 pl-3 pr-4 sm:pr-6 text-sm text-right">
                                @if($product->getStock() > 10)
                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                        {{ $product->getStock() }} unidades
                                    </span>
                                @elseif($product->getStock() > 0)
                                    <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                        Solo {{ $product->getStock() }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                                        Agotado
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-3 py-14 text-center text-sm text-slate-500">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-slate-900">No hay productos</h3>
                                <p class="mt-1 text-sm text-slate-500">Agrega productos para comenzar a vender.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
