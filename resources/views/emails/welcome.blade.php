<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Добро пожаловать!</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 40px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Добро пожаловать в Laravel Shop!</h1>
        <p>Здравствуйте, <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>!</p>
        <p>Благодарим вас за регистрацию в нашем магазине.</p>
        <p>Теперь вы можете оформлять заказы и следить за историей покупок.</p>
        <p>
            <a href="{{ url('/products') }}" class="btn">Перейти в каталог</a>
        </p>
        <p>С уважением,<br>команда Laravel Shop</p>
    </div>
</body>
</html>
