@extends('layouts.app')
@section('title', 'Nueva Venta')
@section('header_title', 'Registrar Nueva Venta')

@section('content')
<div class="mb-6">
    <a href="{{ route('sales.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">
        <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
        </svg>
        Volver al listado
    </a>
</div>

<div class="bg-white shadow-sm ring-1 ring-slate-200 sm:rounded-xl md:col-span-2" 
     x-data="saleForm()">
    <form method="POST" action="{{ route('sales.store') }}" id="saleForm" @submit="isSubmitting = true">
        @csrf
        <div class="px-4 py-6 sm:p-8">
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                
                <!-- Cliente -->
                <div class="sm:col-span-3">
                    <label for="customerId" class="block text-sm font-medium leading-6 text-slate-900">Cliente</label>
                    <div class="mt-2">
                        <select id="customerId" name="customerId" required
                                class="block w-full rounded-md border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="">— Seleccionar cliente —</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->CustomerId }}" {{ old('customerId') == $customer->CustomerId ? 'selected' : '' }}>
                                    {{ $customer->LastName }}, {{ $customer->FirstName }} ({{ $customer->DocumentNumber }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Método de Pago -->
                <div class="sm:col-span-3">
                    <label for="paymentMethodId" class="block text-sm font-medium leading-6 text-slate-900">Método de Pago</label>
                    <div class="mt-2">
                        <select id="paymentMethodId" name="paymentMethodId" required
                                class="block w-full rounded-md border-0 py-2.5 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="">— Seleccionar método —</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->PaymentMethodId }}" {{ old('paymentMethodId') == $method->PaymentMethodId ? 'selected' : '' }}>
                                    {{ $method->Name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Líneas de Venta -->
                <div class="sm:col-span-6 mt-4">
                    <div class="flex items-center justify-between mb-4 border-b border-slate-200 pb-4">
                        <h3 class="text-base font-semibold leading-6 text-slate-900">Líneas de Venta</h3>
                        <button type="button" @click="addRow()"
                                class="inline-flex items-center rounded-md bg-white px-3 py-1.5 text-sm font-semibold text-indigo-600 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors">
                            <svg class="-ml-0.5 mr-1.5 h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                            </svg>
                            Agregar Producto
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-300">
                            <thead>
                                <tr>
                                    <th scope="col" class="py-3 text-left text-xs font-semibold text-slate-900 uppercase tracking-wide">Producto</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-slate-900 uppercase tracking-wide w-32">Cant.</th>
                                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-slate-900 uppercase tracking-wide w-40">Precio Unit.</th>
                                    <th scope="col" class="px-3 py-3 text-right text-xs font-semibold text-slate-900 uppercase tracking-wide w-32">Subtotal</th>
                                    <th scope="col" class="relative py-3 pl-3 pr-4 sm:pr-0 w-12"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <template x-for="(row, index) in rows" :key="row.id">
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-3 pr-3">
                                            <select :name="`items[${index}][productId]`" x-model="row.productId" @change="updatePrice(row)" required
                                                    class="block w-full rounded-md border-0 py-2 pl-3 pr-10 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                                <option value="">— Seleccionar —</option>
                                                <template x-for="product in productsList" :key="product.id">
                                                    <option :value="product.id" x-text="`${product.name} (Stock: ${product.stock})`"></option>
                                                </template>
                                            </select>
                                        </td>
                                        <td class="px-3 py-3">
                                            <input type="number" :name="`items[${index}][quantity]`" x-model.number="row.quantity" min="1" required
                                                   class="block w-full rounded-md border-0 py-2 px-3 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        </td>
                                        <td class="px-3 py-3">
                                            <div class="relative rounded-md shadow-sm">
                                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                    <span class="text-slate-500 sm:text-sm">$</span>
                                                </div>
                                                <input type="number" :name="`items[${index}][unitPrice]`" x-model.number="row.unitPrice" step="0.01" min="0.01" required
                                                       class="block w-full rounded-md border-0 py-2 pl-7 pr-3 text-slate-900 ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 text-right text-sm font-medium text-slate-900">
                                            $ <span x-text="(row.quantity * row.unitPrice || 0).toFixed(2)"></span>
                                        </td>
                                        <td class="py-3 pl-3 text-right">
                                            <button type="button" @click="removeRow(row.id)" x-show="rows.length > 1"
                                                    class="text-red-500 hover:text-red-700 transition-colors p-2 rounded-md hover:bg-red-50">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.808a2.75 2.75 0 002.74-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot class="border-t border-slate-200">
                                <tr>
                                    <th scope="row" colspan="3" class="pt-6 px-3 text-right text-sm font-normal text-slate-600">Subtotal</th>
                                    <td class="pt-6 px-3 text-right text-sm text-slate-900">$ <span x-text="calculateSubtotal().toFixed(2)"></span></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row" colspan="3" class="pt-4 px-3 text-right text-sm font-normal text-slate-600">IVA (15%)</th>
                                    <td class="pt-4 px-3 text-right text-sm text-slate-900">$ <span x-text="(calculateSubtotal() * 0.15).toFixed(2)"></span></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row" colspan="3" class="pt-4 px-3 text-right text-sm font-semibold text-slate-900">Total</th>
                                    <td class="pt-4 px-3 text-right text-base font-semibold text-slate-900">$ <span x-text="(calculateSubtotal() * 1.15).toFixed(2)"></span></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <div class="flex items-center justify-end gap-x-4 border-t border-slate-200 px-4 py-4 sm:px-8 bg-slate-50/50 rounded-b-xl">
            <button type="button" onclick="window.location='{{ route('sales.index') }}'"
                    class="text-sm font-semibold leading-6 text-slate-900 hover:text-slate-700 transition-colors">
                Cancelar
            </button>
            <button type="submit" 
                    :disabled="isSubmitting"
                    class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all disabled:opacity-70 disabled:cursor-not-allowed">
                <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="isSubmitting ? 'Procesando...' : 'Registrar Venta'"></span>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Datos inyectados desde Blade a JS para uso en Alpine
    const productsData = [
        @foreach($products as $product)
        {
            id: '{{ $product->getProductId() }}',
            name: '{{ addslashes($product->getName()) }}',
            stock: {{ $product->getStock() }},
            price: {{ $product->getPrice() }}
        },
        @endforeach
    ];

    document.addEventListener('alpine:init', () => {
        Alpine.data('saleForm', () => ({
            isSubmitting: false,
            productsList: productsData,
            rows: [
                { id: Date.now(), productId: '', quantity: 1, unitPrice: 0 }
            ],
            addRow() {
                this.rows.push({ id: Date.now(), productId: '', quantity: 1, unitPrice: 0 });
            },
            removeRow(id) {
                if (this.rows.length > 1) {
                    this.rows = this.rows.filter(row => row.id !== id);
                }
            },
            updatePrice(row) {
                const product = this.productsList.find(p => p.id == row.productId);
                if (product) {
                    row.unitPrice = product.price;
                }
            },
            calculateSubtotal() {
                return this.rows.reduce((sum, row) => {
                    const q = parseFloat(row.quantity) || 0;
                    const p = parseFloat(row.unitPrice) || 0;
                    return sum + (q * p);
                }, 0);
            }
        }));
    });
</script>
@endpush
@endsection
