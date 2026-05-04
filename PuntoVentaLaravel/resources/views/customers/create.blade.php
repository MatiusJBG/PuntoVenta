@extends('layouts.app')
@section('title', 'Nuevo Cliente')
@section('header_title', 'Registrar Nuevo Cliente')

@section('content')
<div class="mb-6">
    <a href="{{ route('customers.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">
        <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Volver al directorio
    </a>
</div>

<div class="bg-white shadow-sm ring-1 ring-slate-200 sm:rounded-xl md:col-span-2 max-w-2xl" x-data="{ isSubmitting: false }">
    <form method="POST" action="{{ route('customers.store') }}" @submit="isSubmitting = true">
        @csrf
        <div class="px-4 py-6 sm:p-8">
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                
                <div class="sm:col-span-6">
                    <label for="documentNumber" class="block text-sm font-medium leading-6 text-slate-900">Número de Documento</label>
                    <div class="mt-2">
                        <input type="text" name="documentNumber" id="documentNumber" value="{{ old('documentNumber') }}" maxlength="10" required placeholder="Ej: 1790000000 o 9999999999"
                               class="block w-full rounded-md border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="firstName" class="block text-sm font-medium leading-6 text-slate-900">Nombres</label>
                    <div class="mt-2">
                        <input type="text" name="firstName" id="firstName" value="{{ old('firstName') }}" maxlength="100" required
                               class="block w-full rounded-md border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="lastName" class="block text-sm font-medium leading-6 text-slate-900">Apellidos</label>
                    <div class="mt-2">
                        <input type="text" name="lastName" id="lastName" value="{{ old('lastName') }}" maxlength="100" required
                               class="block w-full rounded-md border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="phone" class="block text-sm font-medium leading-6 text-slate-900">Teléfono <span class="text-slate-400 font-normal">(Opcional)</span></label>
                    <div class="mt-2">
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" maxlength="10"
                               class="block w-full rounded-md border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <label for="email" class="block text-sm font-medium leading-6 text-slate-900">Email <span class="text-slate-400 font-normal">(Opcional)</span></label>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" value="{{ old('email') }}" maxlength="150"
                               class="block w-full rounded-md border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-6">
                    <label for="address" class="block text-sm font-medium leading-6 text-slate-900">Dirección <span class="text-slate-400 font-normal">(Opcional)</span></label>
                    <div class="mt-2">
                        <input type="text" name="address" id="address" value="{{ old('address') }}" maxlength="200"
                               class="block w-full rounded-md border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="sm:col-span-6">
                    <label for="city" class="block text-sm font-medium leading-6 text-slate-900">Ciudad <span class="text-slate-400 font-normal">(Opcional)</span></label>
                    <div class="mt-2">
                        <input type="text" name="city" id="city" value="{{ old('city') }}" maxlength="100"
                               class="block w-full rounded-md border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex items-center justify-end gap-x-4 border-t border-slate-200 px-4 py-4 sm:px-8 bg-slate-50/50 rounded-b-xl">
            <button type="button" onclick="window.location='{{ route('customers.index') }}'"
                    class="text-sm font-semibold leading-6 text-slate-900 hover:text-slate-700 transition-colors">
                Cancelar
            </button>
            <button type="submit" :disabled="isSubmitting"
                    class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all disabled:opacity-70 disabled:cursor-not-allowed">
                <svg x-show="isSubmitting" style="display: none;" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="isSubmitting ? 'Guardando...' : 'Guardar Cliente'"></span>
            </button>
        </div>
    </form>
</div>
@endsection
