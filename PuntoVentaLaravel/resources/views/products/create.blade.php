@extends('layouts.app')
@section('title', 'Nuevo Producto')

@section('content')
<h1>Registrar Producto</h1>

<a href="{{ route('products.index') }}" class="btn" style="margin-bottom:16px;display:inline-block;">← Volver</a>

<form method="POST" action="{{ route('products.store') }}" style="max-width:400px;">
    @csrf

    <label>Nombre del Producto</label>
    <input type="text" name="name" value="{{ old('name') }}" maxlength="150" required>

    <label>Precio (USD)</label>
    <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0.01" max="1000000" required>

    <label>Stock Inicial</label>
    <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" max="100000" required>

    <div style="margin-top:18px;">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
@endsection
