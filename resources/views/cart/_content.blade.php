@if($items->isEmpty())
    <div class="alert alert-info mb-0">
        Корзина пуста. <a href="{{ route('products.index') }}">Перейти в каталог</a>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    @php($product = $item->product)
                    <tr>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            @if($product->category)
                                <br><small class="text-muted">{{ $product->category->name }}</small>
                            @endif
                        </td>
                        <td>{{ number_format((float) $item->unit_price, 0, ',', ' ') }} ₽</td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <form method="POST" 
                                      action="{{ route('cart.items.update', $product) }}" 
                                      data-ajax-cart="1" 
                                      data-cart-action="update" 
                                      class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm" 
                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                        -
                                    </button>
                                </form>

                                <form method="POST" 
                                      action="{{ route('cart.items.update', $product) }}" 
                                      data-ajax-cart="1" 
                                      data-cart-action="set" 
                                      class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" 
                                           name="quantity" 
                                           value="{{ $item->quantity }}" 
                                           min="1" 
                                           max="{{ $product->stock }}" 
                                           step="1" 
                                           class="form-control form-control-sm text-center" 
                                           style="width: 70px;"
                                           data-cart-action="set">
                                </form>

                                <form method="POST" 
                                      action="{{ route('cart.items.update', $product) }}" 
                                      data-ajax-cart="1" 
                                      data-cart-action="update" 
                                      class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm" 
                                            {{ $item->quantity >= $product->stock ? 'disabled' : '' }}>
                                        +
                                    </button>
                                </form>
                            </div>
                        </td>
                        <td><strong>{{ number_format((float) $item->subtotal, 0, ',', ' ') }} ₽</strong></td>
                        <td>
                            <form method="POST" action="{{ route('cart.items.destroy', $product) }}" class="d-inline" data-ajax-cart="1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-group-divider">
                <tr>
                    <th colspan="3" class="text-end">Итого:</th>
                    <th class="fs-5">{{ number_format((float) $totalPrice, 0, ',', ' ') }} ₽</th>
                    <th></th>
                </tr>
                <tr>
                    <td colspan="5" class="text-end text-muted">
                        Всего товаров: <strong>{{ $totalQuantity }}</strong>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="d-flex gap-2 mt-3">
        <form method="POST" action="{{ route('cart.clear') }}" class="d-inline" data-ajax-cart="1">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Очистить корзину</button>
        </form>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Продолжить покупки</a>
    </div>
@endif
