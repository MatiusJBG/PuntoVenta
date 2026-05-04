<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Factura {{ $invoiceDocument->invoiceNumber }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #222; }

        /* ── Layout ── */
        .page { padding: 30px 35px; }

        /* ── Header ── */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .company-cell { width: 60%; vertical-align: top; }
        .invoice-cell { width: 40%; vertical-align: top; text-align: right; }
        .company-name { font-size: 18px; font-weight: bold; color: #111; }
        .company-ruc  { font-size: 10px; color: #555; margin-top: 2px; }
        .company-info { font-size: 10px; color: #555; margin-top: 4px; line-height: 1.5; }
        .invoice-badge { display: inline-block; background: #111; color: #fff;
                         padding: 4px 10px; font-size: 10px; letter-spacing: 1px;
                         text-transform: uppercase; }
        .invoice-number { font-size: 15px; font-weight: bold; margin-top: 6px; color: #111; }
        .invoice-date   { font-size: 10px; color: #555; margin-top: 4px; }

        /* ── Divider ── */
        .divider { border: none; border-top: 1.5px solid #111; margin: 14px 0; }

        /* ── Customer Section ── */
        .section-title { font-size: 9px; font-weight: bold; text-transform: uppercase;
                         letter-spacing: 1px; color: #888; margin-bottom: 6px; }
        .customer-table { width: 100%; border-collapse: collapse; }
        .customer-label { width: 28%; color: #666; font-size: 10px; padding: 2px 0; vertical-align: top; }
        .customer-value { color: #111; font-size: 10px; padding: 2px 0; vertical-align: top; }

        /* ── Details Table ── */
        .details-table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        .details-table thead tr { background: #111; color: #fff; }
        .details-table th { padding: 7px 8px; font-size: 10px; text-align: left;
                            text-transform: uppercase; letter-spacing: 0.5px; }
        .details-table th.right, .details-table td.right { text-align: right; }
        .details-table td { padding: 6px 8px; font-size: 10px; border-bottom: 1px solid #e0e0e0; }
        .details-table tbody tr:nth-child(even) { background: #f9f9f9; }

        /* ── Totals ── */
        .totals-wrapper { margin-top: 16px; }
        .totals-table { width: 280px; float: right; border-collapse: collapse; }
        .totals-table td { padding: 4px 8px; font-size: 10px; }
        .totals-table .label { color: #666; text-align: left; }
        .totals-table .amount { text-align: right; color: #111; }
        .totals-total td { border-top: 2px solid #111; font-weight: bold; font-size: 12px; padding-top: 8px; }
        .clearfix::after { content: ''; display: table; clear: both; }

        /* ── Payment footer ── */
        .payment-section { margin-top: 28px; border-top: 1px solid #ddd; padding-top: 10px; }
        .payment-label { font-size: 9px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        .payment-value { font-size: 11px; font-weight: bold; color: #111; margin-top: 2px; }

        /* ── Legal footer ── */
        .legal-footer { margin-top: 24px; border-top: 1px solid #ddd; padding-top: 8px;
                        font-size: 9px; color: #aaa; text-align: center; }
    </style>
</head>
<body>
<div class="page">

    {{-- ── HEADER ── --}}
    <table class="header-table">
        <tr>
            <td class="company-cell">
                <div class="company-name">{{ $invoiceDocument->companyName }}</div>
                <div class="company-ruc">RUC: {{ $invoiceDocument->companyRuc }}</div>
                <div class="company-info">
                    {{ $invoiceDocument->companyAddress }}<br>
                    Tel: {{ $invoiceDocument->companyPhone }}
                </div>
            </td>
            <td class="invoice-cell">
                <div class="invoice-badge">Factura</div>
                <div class="invoice-number">N° {{ $invoiceDocument->invoiceNumber }}</div>
                <div class="invoice-date">
                    Fecha: {{ $invoiceDocument->emissionDateTime->format('d/m/Y H:i') }}
                </div>
            </td>
        </tr>
    </table>

    <hr class="divider">

    {{-- ── CUSTOMER SECTION ── --}}
    <div class="section-title">Datos del cliente</div>
    <table class="customer-table">
        <tr>
            <td class="customer-label">Razón Social:</td>
            <td class="customer-value">{{ $invoiceDocument->customerFullName }}</td>
            <td class="customer-label">Identificación:</td>
            <td class="customer-value">{{ $invoiceDocument->customerDocumentNumber }}</td>
        </tr>
        <tr>
            <td class="customer-label">Dirección:</td>
            <td class="customer-value">{{ $invoiceDocument->customerAddress ?? '—' }}</td>
            <td class="customer-label">Ciudad:</td>
            <td class="customer-value">{{ $invoiceDocument->customerCity ?? '—' }}</td>
        </tr>
        <tr>
            <td class="customer-label">Teléfono:</td>
            <td class="customer-value">{{ $invoiceDocument->customerPhone ?? '—' }}</td>
            <td></td><td></td>
        </tr>
    </table>

    {{-- ── DETAILS TABLE ── --}}
    <table class="details-table">
        <thead>
            <tr>
                <th style="width:8%">Código</th>
                <th style="width:44%">Descripción</th>
                <th class="right" style="width:10%">Cant.</th>
                <th class="right" style="width:16%">Precio Unit.</th>
                <th class="right" style="width:16%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoiceDocument->lineItemDetails as $lineItem)
            <tr>
                <td>{{ str_pad($lineItem->productCode, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $lineItem->productDescription }}</td>
                <td class="right">{{ $lineItem->quantity }}</td>
                <td class="right">$ {{ number_format($lineItem->unitPrice, 2) }}</td>
                <td class="right">$ {{ number_format($lineItem->lineSubtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ── TOTALS ── --}}
    <div class="totals-wrapper clearfix">
        <table class="totals-table">
            <tr>
                <td class="label">Subtotal (base IVA {{ $invoiceDocument->getFormattedTaxRateLabel() }}):</td>
                <td class="amount">$ {{ number_format($invoiceDocument->subtotalAmount, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Subtotal 0%:</td>
                <td class="amount">$ 0.00</td>
            </tr>
            <tr>
                <td class="label">IVA ({{ $invoiceDocument->getFormattedTaxRateLabel() }}):</td>
                <td class="amount">$ {{ number_format($invoiceDocument->taxAmount, 2) }}</td>
            </tr>
            <tr class="totals-total">
                <td class="label">VALOR TOTAL:</td>
                <td class="amount">$ {{ number_format($invoiceDocument->totalAmount, 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- ── PAYMENT & FOOTER ── --}}
    <div class="payment-section">
        <div class="payment-label">Forma de Pago</div>
        <div class="payment-value">{{ $invoiceDocument->paymentMethodName }}</div>
    </div>

    <div class="legal-footer">
        Documento generado electrónicamente — {{ $invoiceDocument->companyName }} |
        RUC {{ $invoiceDocument->companyRuc }} |
        {{ $invoiceDocument->emissionDateTime->format('Y') }}
    </div>

</div>
</body>
</html>
