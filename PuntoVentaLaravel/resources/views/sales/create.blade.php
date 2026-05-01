@extends('layouts.app')
@section('title', 'Nueva Venta')

@section('content')
<h1>Registrar Venta</h1>

<a href="{{ route('sales.index') }}" class="btn" style="margin-bottom:16px;display:inline-block;">← Volver</a>

<form method="POST" action="{{ route('sales.store') }}" id="saleForm" style="max-width:700px;">
    @csrf

    <label>Cliente</label>
    <select name="customerId" required>
        <option value="">— Seleccionar —</option>
        @foreach($customers as $customer)
            <option value="{{ $customer->CustomerId }}" {{ old('customerId') == $customer->CustomerId ? 'selected' : '' }}>
                {{ $customer->LastName }}, {{ $customer->FirstName }} ({{ $customer->DocumentNumber }})
            </option>
        @endforeach
    </select>

    <label>Método de Pago</label>
    <select name="paymentMethodId" required>
        <option value="">— Seleccionar —</option>
        @foreach($paymentMethods as $method)
            <option value="{{ $method->PaymentMethodId }}" {{ old('paymentMethodId') == $method->PaymentMethodId ? 'selected' : '' }}>
                {{ $method->Name }}
            </option>
        @endforeach
    </select>

    <div style="margin-top:20px; border-top:1px solid #ccc; padding-top:16px;">
        <strong>Líneas de Venta</strong>

        <table id="itemsTable" style="width:100%; margin-top:10px; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="border:1px solid #ddd;padding:6px;">Producto</th>
                    <th style="border:1px solid #ddd;padding:6px;width:80px;">Cant.</th>
                    <th style="border:1px solid #ddd;padding:6px;width:110px;">Precio Unit.</th>
                    <th style="border:1px solid #ddd;padding:6px;width:40px;"></th>
                </tr>
            </thead>
            <tbody id="itemsBody">
                <tr id="item-row-0">
                    <td style="border:1px solid #ddd;padding:4px;">
                        <select name="items[0][productId]" class="product-select" style="width:100%;" required>
                            <option value="">— Seleccionar —</option>
                            @foreach($products as $product)
                                <option value="{{ $product->getProductId() }}" data-price="{{ $product->getPrice() }}">
                                    {{ $product->getName() }} (Stock: {{ $product->getStock() }})
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td style="border:1px solid #ddd;padding:4px;">
                        <input type="number" name="items[0][quantity]" min="1" value="1" style="width:100%;" required>
                    </td>
                    <td style="border:1px solid #ddd;padding:4px;">
                        <input type="number" name="items[0][unitPrice]" step="0.01" min="0.01" class="price-input" style="width:100%;" required>
                    </td>
                    <td style="border:1px solid #ddd;padding:4px;text-align:center;">—</td>
                </tr>
            </tbody>
        </table>

        <button type="button" id="addItemBtn" class="btn" style="margin-top:8px;">+ Agregar línea</button>
    </div>

    <div style="margin-top:18px;">
        <button type="submit" class="btn btn-primary">Registrar Venta</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    let rowIndex = 1;

    const productOptions = `
        @foreach($products as $product)
            <option value="{{ $product->getProductId() }}" data-price="{{ $product->getPrice() }}">{{ $product->getName() }} (Stock: {{ $product->getStock() }})</option>
        @endforeach
    `;

    document.getElementById('addItemBtn').addEventListener('click', function () {
        const tbody = document.getElementById('itemsBody');
        const row   = document.createElement('tr');
        row.id      = `item-row-${rowIndex}`;
        row.innerHTML = `
            <td style="border:1px solid #ddd;padding:4px;">
                <select name="items[${rowIndex}][productId]" class="product-select" style="width:100%;" required>
                    <option value="">— Seleccionar —</option>
                    ${productOptions}
                </select>
            </td>
            <td style="border:1px solid #ddd;padding:4px;">
                <input type="number" name="items[${rowIndex}][quantity]" min="1" value="1" style="width:100%;" required>
            </td>
            <td style="border:1px solid #ddd;padding:4px;">
                <input type="number" name="items[${rowIndex}][unitPrice]" step="0.01" min="0.01" class="price-input" style="width:100%;" required>
            </td>
            <td style="border:1px solid #ddd;padding:4px;text-align:center;">
                <button type="button" onclick="this.closest('tr').remove()" class="btn" style="padding:2px 8px;">✕</button>
            </td>
        `;
        tbody.appendChild(row);
        attachPriceAutoFill(row.querySelector('.product-select'));
        rowIndex++;
    });

    function attachPriceAutoFill(selectElement) {
        selectElement.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const price          = selectedOption.getAttribute('data-price');
            const priceInput     = this.closest('tr').querySelector('.price-input');
            if (price) priceInput.value = parseFloat(price).toFixed(2);
        });
    }

    // Attach to first row
    document.querySelectorAll('.product-select').forEach(attachPriceAutoFill);
</script>
@endpush
