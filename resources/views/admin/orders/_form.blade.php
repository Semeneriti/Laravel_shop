<div class="mb-3">
    <label for="user_id" class="form-label">Пользователь</label>
    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected(old('user_id', $order->user_id ?? '') == $u->id)>
                {{ $u->first_name }} {{ $u->last_name }} ({{ $u->email }})
            </option>
        @endforeach
    </select>
    @error('user_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="status" class="form-label">Статус</label>
    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
        @foreach(\App\Models\Order::STATUS_LABELS as $key => $label)
            <option value="{{ $key }}" @selected(old('status', $order->status ?? 'pending') == $key)>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('status')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Товары в заказе</label>
    <div id="order-items">
        @php($items = old('items', $order->items ?? []))
        @if(count($items))
            @foreach($items as $index => $item)
                <div class="row mb-2 item-row">
                    <div class="col-4">
                        <select name="items[{{ $index }}][product_id]" class="form-control" required>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" @selected(isset($item['product_id']) && $item['product_id'] == $product->id)>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="number" name="items[{{ $index }}][quantity]" class="form-control" placeholder="Кол-во" value="{{ $item['quantity'] ?? 1 }}" min="1" required>
                    </div>
                    <div class="col-3">
                        <input type="number" name="items[{{ $index }}][price]" class="form-control" placeholder="Цена" value="{{ $item['price'] ?? 0 }}" step="0.01" min="0" required>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-danger remove-item">×</button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="row mb-2 item-row">
                <div class="col-4">
                    <select name="items[0][product_id]" class="form-control" required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-3">
                    <input type="number" name="items[0][quantity]" class="form-control" placeholder="Кол-во" value="1" min="1" required>
                </div>
                <div class="col-3">
                    <input type="number" name="items[0][price]" class="form-control" placeholder="Цена" value="0" step="0.01" min="0" required>
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-danger remove-item">×</button>
                </div>
            </div>
        @endif
    </div>
    <button type="button" id="add-item" class="btn btn-secondary btn-sm mt-2">+ Добавить товар</button>
    @error('items')
    <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('order-items');
        const addBtn = document.getElementById('add-item');
        let index = container.querySelectorAll('.item-row').length;

        addBtn.addEventListener('click', function () {
            const row = document.createElement('div');
            row.className = 'row mb-2 item-row';
            row.innerHTML = ;
            container.appendChild(row);
            index++;
            row.querySelector('.remove-item').addEventListener('click', function () {
                row.remove();
            });
        });

        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function () {
                this.closest('.item-row').remove();
            });
        });
    });
</script>
