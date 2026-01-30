@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="row">
    <div class="col-md-6">
        <img src="https://via.placeholder.com/500x400?text={{ urlencode($product->name) }}" 
             class="img-fluid" 
             alt="{{ $product->name }}">
    </div>
    <div class="col-md-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produits</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.category', $product->category) }}">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>

        <h1>{{ $product->name }}</h1>
        
        <p class="lead">
            <span class="price">{{ number_format($product->price, 2) }} €</span>
        </p>

        <p class="text-muted">
            <i class="bi bi-tag"></i> Catégorie: {{ $product->category->name }}
        </p>

        <p>{{ $product->description }}</p>

        @if($product->stock > 0)
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> {{ $product->stock }} article(s) en stock
            </div>
            
            <form method="POST" action="{{ route('cart.add', $product) }}" class="mb-4">
                @csrf
                <div class="input-group mb-3" style="max-width: 200px;">
                    <label class="input-group-text">Quantité</label>
                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}">
                </div>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-cart-plus"></i> Ajouter au Panier
                </button>
            </form>
        @else
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-circle"></i> Ce produit est actuellement rupture de stock
            </div>
            <button class="btn btn-secondary btn-lg" disabled>
                <i class="bi bi-x-circle"></i> Indisponible
            </button>
        @endif

        <hr>

        <h5>Partager ce produit</h5>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary">
                <i class="bi bi-facebook"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary">
                <i class="bi bi-twitter"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary">
                <i class="bi bi-linkedin"></i>
            </button>
        </div>
    </div>
</div>

<!-- Produits Associés -->
@if($relatedProducts->count() > 0)
    <hr class="my-5">
    <h3>Produits Associés</h3>
    <div class="row">
        @foreach($relatedProducts as $related)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/300x250?text={{ urlencode($related->name) }}" 
                         class="card-img-top product-image" 
                         alt="{{ $related->name }}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $related->name }}</h6>
                        <p class="price mt-auto">{{ number_format($related->price, 2) }} €</p>
                        <a href="{{ route('products.show', $related) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> Voir
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
