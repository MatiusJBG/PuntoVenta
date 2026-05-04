@extends('layouts.app')
@section('title', 'Ventas')
@section('header_title', 'Gestión de Ventas')

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-base font-semibold leading-6 text-slate-900">Listado de Ventas</h2>
        <p class="mt-2 text-sm text-slate-600">Historial de transacciones, descargas de facturas y anulaciones.</p>
    </div>
    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <a href="{{ route('sales.create') }}" 
           class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-200">
            <svg class="-ml-1 mr-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
            </svg>
            Nueva Venta
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
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900 uppercase tracking-wide">Cliente ID</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-xs font-semibold text-slate-900 uppercase tracking-wide">Fecha</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-xs font-semibold text-slate-900 uppercase tracking-wide">Subtotal</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-xs font-semibold text-slate-900 uppercase tracking-wide">Total</th>
                            <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold text-slate-900 uppercase tracking-wide">Estado</th>
                            <th scope="col" class="px-3 py-3.5 text-center text-xs font-semibold text-slate-900 uppercase tracking-wide">Factura</th>
                            <th scope="col" class="py-3.5 pl-3 pr-4 sm:pr-6 text-right text-xs font-semibold text-slate-900 uppercase tracking-wide">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($sales as $sale)
                        <tr class="hover:bg-slate-50 transition-colors duration-150 {{ $sale->isVoided() ? 'opacity-60 bg-slate-50/50' : '' }}">
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-slate-900 sm:pl-6">
                                #{{ str_pad($sale->getSaleId(), 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                                {{ $sale->getCustomerId() }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                                {{ $sale->getSaleDate()->format('d M, Y H:i') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600 text-right">
                                $ {{ number_format($sale->getSubtotal(), 2) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-slate-900 text-right">
                                $ {{ number_format($sale->getTotal(), 2) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                                @if($sale->isCompleted())
                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                        Completada
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                                        Anulada
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('invoices.pdf', $sale->getSaleId()) }}" title="Descargar PDF"
                                       class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-md text-xs font-medium transition-colors">
                                        PDF
                                    </a>
                                    <a href="{{ route('invoices.xml', $sale->getSaleId()) }}" title="Descargar XML"
                                       class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded-md text-xs font-medium transition-colors">
                                        XML
                                    </a>
                                </div>
                            </td>
                            <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                @if($sale->isCompleted())
                                    <form method="POST" action="{{ route('sales.void', $sale->getSaleId()) }}" class="inline">
                                        @csrf
                                        <button type="button" 
                                                onclick="confirmVoidSale(this)"
                                                class="text-red-600 hover:text-red-900 font-medium transition-colors">
                                            Anular
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-3 py-14 text-center text-sm text-slate-500">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-semibold text-slate-900">No hay ventas</h3>
                                <p class="mt-1 text-sm text-slate-500">No se encontraron registros de ventas en el sistema.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmVoidSale(button) {
        window.Swal.fire({
            title: '¿Confirmar anulación?',
            text: 'Esta acción restaurará el stock de los productos. No se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, anular factura',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>
@endpush
@endsection
