<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel Shop'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-light bg-light mb-4">
    <div class="container">
        <a href="{{ route('home') }}" class="navbar-brand">Laravel Shop</a>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('products.index') }}" class="nav-link">Каталог</a>
            <a href="{{ route('categories.index') }}" class="nav-link">Категории</a>

            @php($cartCount = collect(session('cart.items', []))->sum())
            <a href="{{ route('cart.index') }}" class="btn btn-outline-primary btn-sm position-relative">
                Корзина
                <span class="badge text-bg-secondary ms-1" data-cart-count>{{ $cartCount }}</span>
            </a>

            @guest
                <a href="{{ route('login.form') }}" class="btn btn-primary btn-sm">Вход</a>
                <a href="{{ route('register.form') }}" class="btn btn-outline-secondary btn-sm">Регистрация</a>
            @endguest

            @auth
                <a href="{{ route('profile.form') }}" class="btn btn-outline-secondary btn-sm">Профиль</a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Выход</button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<main class="container">
    @yield('content')
</main>
<script>
    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function setCartCount(count) {
        var badge = document.querySelector('[data-cart-count]');
        if (badge) {
            badge.textContent = count;
        }
    }

    async function submitCartForm(form) {
        var formData = new FormData(form);
        var response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: formData,
        });

        if (response.ok === false) {
            return;
        }

        var data = await response.json();

        if (typeof data.cartCount !== 'undefined') {
            setCartCount(data.cartCount);
        }

        var cartContent = document.getElementById('cart-content');
        if (cartContent && typeof data.html === 'string') {
            cartContent.innerHTML = data.html;
        }

        attachCartEvents();
    }

    function attachCartEvents() {
        var forms = document.querySelectorAll('form[data-ajax-cart]');
        for (var i = 0; i < forms.length; i++) {
            var form = forms[i];
            if (form.dataset.listener) continue;
            form.dataset.listener = 'true';
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                submitCartForm(this);
            });
        }

        var inputs = document.querySelectorAll('input[data-cart-action="set"]');
        for (var j = 0; j < inputs.length; j++) {
            var input = inputs[j];
            if (input.dataset.listener) continue;
            input.dataset.listener = 'true';
            input.addEventListener('change', function(e) {
                var form = this.closest('form[data-ajax-cart]');
                if (form) {
                    submitCartForm(form);
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        attachCartEvents();
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
