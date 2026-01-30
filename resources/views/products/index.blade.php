@extends('layouts.app')

@section('title', 'Catalogue de Produits')

@section('content')
<div class="row mb-5">
    <div class="col-md-12">
        <h1 class="mb-4">
            <i class="bi bi-shop"></i> 
            @if(isset($category))
                Catégorie: {{ $category->name }}
            @else
                Catalogue de Produits
            @endif
        </h1>

        <!-- Barre de recherche -->
        <form method="GET" action="{{ route('products.search') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Rechercher un produit..." value="{{ $search ?? '' }}">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i> Rechercher
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <!-- Sidebar Catégories -->
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-header">
                <h5>Catégories</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('products.index') }}" class="list-group-item list-group-item-action {{ !isset($category) ? 'active' : '' }}">
                    Tous les produits
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('products.category', $cat) }}" class="list-group-item list-group-item-action {{ isset($category) && $category->id === $cat->id ? 'active' : '' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Produits -->
    <div class="col-md-9">
        @if($products->count() > 0)
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <img src="https://via.placeholder.com/300x250?text={{ urlencode($product->name) }}" 
                                 class="card-img-top product-image" 
                                 alt="{{ $product->name }}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text text-muted small">{{ Str::limit($product->description, 100) }}</p>
                                <p class="text-secondary small">
                                    <i class="bi bi-tag"></i> {{ $product->category->name }}
                                </p>
                                <p class="price mt-auto">{{ number_format($product->price, 2) }} €</p>
                                
                                @if($product->stock > 0)
                                    <div class="alert alert-success py-1 px-2 small" role="alert">
                                        <i class="bi bi-check-circle"></i> {{ $product->stock }} en stock
                                    </div>
                                @else
                                    <div class="alert alert-warning py-1 px-2 small" role="alert">
                                        <i class="bi bi-exclamation-circle"></i> Rupture de stock
                                    </div>
                                @endif

                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-eye"></i> Détails
                                    </a>
                                    @if($product->stock > 0)
                                        <form method="POST" action="{{ route('cart.add', $product) }}" style="flex: 1;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                <i class="bi bi-cart-plus"></i> Ajouter
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                {{ $products->links() }}
            </nav>
        @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Aucun produit trouvé.
            </div>
        @endif
    </div>
</div>
@endsection
