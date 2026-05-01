@extends('layouts.app')
@section('title', 'Productos')

@section('content')
<h1>Productos</h1>

<div class="actions">
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Nuevo producto</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
        <tr>
            <td>{{ $product->getProductId() }}</td>
            <td>{{ $product->getName() }}</td>
            <td>$ {{ number_format($product->getPrice(), 2) }}</td>
            <td>{{ $product->getStock() }}</td>
        </tr>
        @empty
        <tr><td colspan="4">No hay productos registrados.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
