@extends('layouts.app')
@section('title', 'Nuevo Producto')
@section('header_title', 'Registrar Nuevo Producto')

@section('content')
<div class="mb-6">
    <a href="{{ route('products.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">
        <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Volver al catálogo
    </a>
</div>

<div class="bg-white shadow-sm ring-1 ring-slate-200 sm:rounded-xl md:col-span-2 max-w-xl" x-data="{ isSubmitting: false }">
    <form method="POST" action="{{ route('products.store') }}" @submit="isSubmitting = true">
        @csrf
        <div class="px-4 py-6 sm:p-8">
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                
                <div class="sm:col-span-6">
                    <label for="name" class="block text-sm font-medium leading-6 text-slate-900">Nombre del Producto</label>
                    <div class="mt-2">
                        <input type="text" name="name" id="name" value="{{ old('name') }}" maxlength="150" required
                               class="block w-full rounded-md border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="price" class="block text-sm font-medium leading-6 text-slate-900">Precio de Venta</label>
                    <div class="relative mt-2 rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-slate-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0.01" max="1000000" required
                               class="block w-full rounded-md border-0 py-2.5 pl-7 pr-3 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="stock" class="block text-sm font-medium leading-6 text-slate-900">Stock Inicial</label>
                    <div class="mt-2">
                        <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0" max="100000" required
                               class="block w-full rounded-md border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

            </div>
        </div>
        
        <div class="flex items-center justify-end gap-x-4 border-t border-slate-200 px-4 py-4 sm:px-8 bg-slate-50/50 rounded-b-xl">
            <button type="button" onclick="window.location='{{ route('products.index') }}'"
                    class="text-sm font-semibold leading-6 text-slate-900 hover:text-slate-700 transition-colors">
                Cancelar
            </button>
            <button type="submit" :disabled="isSubmitting"
                    class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all disabled:opacity-70 disabled:cursor-not-allowed">
                <svg x-show="isSubmitting" style="display: none;" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="isSubmitting ? 'Guardando...' : 'Guardar Producto'"></span>
            </button>
        </div>
    </form>
</div>
@endsection
