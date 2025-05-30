<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title') - Loja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('produtos.index') }}">Loja</a>
            <a class="nav-link text-white" href="{{ route('carrinho.index') }}">Carrinho</a>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>
</body>
</html>
