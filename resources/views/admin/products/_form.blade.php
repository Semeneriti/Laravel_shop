<div class="mb-3">
    <label for="name" class="form-label">Название</label>
    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name ?? '') }}" required>
    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="price" class="form-label">Цена</label>
    <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price ?? '') }}" step="0.01" required>
    @error('price')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="stock" class="form-label">Количество на складе</label>
    <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock ?? 0) }}" required>
    @error('stock')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="sku" class="form-label">Артикул</label>
    <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku ?? '') }}" required>
    @error('sku')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="category_id" class="form-label">Категория</label>
    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
        <option value="">Без категории</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="status" class="form-label">Статус</label>
    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
        <option value="active" @selected(old('status', $product->status ?? 'active') === 'active')>Активен</option>
        <option value="inactive" @selected(old('status', $product->status ?? 'active') === 'inactive')>Неактивен</option>
    </select>
    @error('status')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="image" class="form-label">Изображение</label>
    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
    @if(isset($product) && $product->image)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-height: 150px;">
        </div>
    @endif
    @error('image')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
