@extends('layouts.app')
@section('title', 'Ventas')

@section('content')
<h1>Ventas</h1>

<div class="actions">
    <a href="{{ route('sales.create') }}" class="btn btn-primary">+ Nueva venta</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Cliente ID</th>
            <th>Fecha</th>
            <th>Subtotal</th>
            <th>IVA</th>
            <th>Total</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sales as $sale)
        <tr>
            <td>{{ $sale->getSaleId() }}</td>
            <td>{{ $sale->getCustomerId() }}</td>
            <td>{{ $sale->getSaleDate()->format('Y-m-d H:i') }}</td>
            <td>$ {{ number_format($sale->getSubtotal(), 2) }}</td>
            <td>$ {{ number_format($sale->getTaxAmount(), 2) }}</td>
            <td>$ {{ number_format($sale->getTotal(), 2) }}</td>
            <td>{{ $sale->getStatusId() === 1 ? 'Completada' : 'Anulada' }}</td>
        </tr>
        @empty
        <tr><td colspan="7">No hay ventas registradas.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
