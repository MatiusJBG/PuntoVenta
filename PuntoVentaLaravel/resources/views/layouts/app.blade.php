<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PuntoVenta — @yield('title', 'Sistema')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: monospace; font-size: 14px; background: #f5f5f5; color: #111; }
        nav { background: #111; padding: 10px 20px; display: flex; gap: 20px; }
        nav a { color: #ccc; text-decoration: none; }
        nav a:hover { color: #fff; }
        nav a.active { color: #fff; font-weight: bold; border-bottom: 1px solid #fff; }
        main { padding: 24px; max-width: 1100px; margin: 0 auto; }
        h1 { font-size: 18px; margin-bottom: 16px; border-bottom: 1px solid #ccc; padding-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ddd; padding: 7px 10px; text-align: left; }
        th { background: #eee; }
        tr:hover { background: #fafafa; }
        .btn { display: inline-block; padding: 6px 14px; border: 1px solid #555; background: #fff;
               text-decoration: none; color: #111; cursor: pointer; font-family: monospace; font-size: 13px; }
        .btn:hover { background: #eee; }
        .btn-primary { background: #111; color: #fff; border-color: #111; }
        .btn-primary:hover { background: #333; }
        form label { display: block; margin-top: 12px; font-size: 12px; text-transform: uppercase; color: #555; }
        form input, form select { width: 100%; padding: 6px 8px; border: 1px solid #bbb;
                                   font-family: monospace; font-size: 14px; margin-top: 4px; }
        .alert-success { background: #dff0d8; border: 1px solid #b2dfdb; padding: 10px; margin-bottom: 14px; color: #2e7d32; }
        .alert-error   { background: #fdecea; border: 1px solid #f5c6cb; padding: 10px; margin-bottom: 14px; color: #c62828; }
        .actions { margin-bottom: 14px; }
    </style>
</head>
<body>

<nav>
    <a href="{{ route('sales.index') }}"     @class(['active' => request()->routeIs('sales.*')])>Ventas</a>
    <a href="{{ route('customers.index') }}" @class(['active' => request()->routeIs('customers.*')])>Clientes</a>
    <a href="{{ route('products.index') }}"  @class(['active' => request()->routeIs('products.*')])>Productos</a>
</nav>

<main>
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @yield('content')
</main>

@stack('scripts')
</body>
</html>
