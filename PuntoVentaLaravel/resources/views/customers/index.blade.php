@extends('layouts.app')
@section('title', 'Clientes')

@section('content')
<h1>Clientes</h1>

<div class="actions">
    <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Nuevo cliente</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Documento</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Ciudad</th>
        </tr>
    </thead>
    <tbody>
        @forelse($customers as $customer)
        <tr>
            <td>{{ $customer->getCustomerId() }}</td>
            <td>{{ $customer->getDocumentNumber() }}</td>
            <td>{{ $customer->getFullName() }}</td>
            <td>{{ $customer->getPhone() ?? '—' }}</td>
            <td>{{ $customer->getEmail() ?? '—' }}</td>
            <td>{{ $customer->getCity() ?? '—' }}</td>
        </tr>
        @empty
        <tr><td colspan="6">No hay clientes registrados.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
