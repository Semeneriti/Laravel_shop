@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
    <div class="container py-4">
        <h1 class="h3 mb-3">Корзина</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div id="cart-content">
            @include('cart._content', [
                'items' => $items,
                'totalQuantity' => $totalQuantity,
                'totalPrice' => $totalPrice
            ])
        </div>

        @if( ! $items->isEmpty())
            <div class="mt-4">
                @if($defaultAddress)
                    <div class="alert alert-info">
                        <strong>Адрес доставки:</strong> {{ $defaultAddress->full_address }}
                        <a href="{{ route('profile.form') }}" class="btn btn-link btn-sm">Изменить</a>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <strong>Внимание!</strong> У вас нет основного адреса доставки.
                        <a href="{{ route('profile.form') }}" class="btn btn-link btn-sm">Добавить адрес</a>
                    </div>
                @endif

                @if($defaultAddress)
                    <form method="POST" action="{{ route('orders.store') }}" class="mt-3">
                        @csrf

                        <h3 class="h6 mb-2">Способ оплаты</h3>

                        <div class="form-check">
                            <input class="form-check-input"
                                   type="radio"
                                   name="payment_method"
                                   id="payment-cash"
                                   value="cash"
                                   @checked(old('payment_method', 'cash') === 'cash')>
                            <label class="form-check-label" for="payment-cash">
                                Наличными при получении
                            </label>
                        </div>

                        <div class="form-check mt-1">
                            <input class="form-check-input"
                                   type="radio"
                                   name="payment_method"
                                   id="payment-card"
                                   value="card"
                                   @checked(old('payment_method') === 'card')>
                            <label class="form-check-label" for="payment-card">
                                Картой при получении
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">
                            Оформить заказ
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>
@endsection
    <div class="form-check mt-1">
        <input class="form-check-input"
               type="radio"
               name="payment_method"
               id="payment-yookassa"
               value="yookassa"
               @checked(old('payment_method') === 'yookassa')>
        <label class="form-check-label" for="payment-yookassa">
            Онлайн через YooKassa: карта, СБП, SberPay, T-Pay, Alfa Pay
        </label>
    </div>
