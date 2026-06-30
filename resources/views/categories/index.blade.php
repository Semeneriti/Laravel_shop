@extends('layouts.app')

@section('title', 'Категории')

@section('content')
    <div class="container py-4">
        <h1 class="h3 mb-3">Категории товаров</h1>

        <div class="row g-4">
            @forelse($categories as $category)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="text-muted small">{{ $category->products()->count() }} товаров</p>
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-primary">
                                Перейти
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted py-5">Категории ещё не добавлены.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
