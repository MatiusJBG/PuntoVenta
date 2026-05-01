@extends('layouts.app')
@section('title', 'Nuevo Cliente')

@section('content')
<h1>Registrar Cliente</h1>

<a href="{{ route('customers.index') }}" class="btn" style="margin-bottom:16px;display:inline-block;">← Volver</a>

<form method="POST" action="{{ route('customers.store') }}" style="max-width:500px;">
    @csrf

    <label>Número de Documento (Cédula / 9999999999 para CF)</label>
    <input type="text" name="documentNumber" value="{{ old('documentNumber') }}" maxlength="10" required>

    <label>Nombres</label>
    <input type="text" name="firstName" value="{{ old('firstName') }}" maxlength="100" required>

    <label>Apellidos</label>
    <input type="text" name="lastName" value="{{ old('lastName') }}" maxlength="100" required>

    <label>Teléfono (opcional, 7–10 dígitos)</label>
    <input type="text" name="phone" value="{{ old('phone') }}" maxlength="10">

    <label>Email (opcional)</label>
    <input type="email" name="email" value="{{ old('email') }}" maxlength="150">

    <label>Dirección (opcional)</label>
    <input type="text" name="address" value="{{ old('address') }}" maxlength="200">

    <label>Ciudad (opcional)</label>
    <input type="text" name="city" value="{{ old('city') }}" maxlength="100">

    <div style="margin-top:18px;">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</form>
@endsection
