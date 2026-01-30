@extends('layouts.app')

@section('title', 'Panier d\'Achat')

@section('content')
<h1 class="mb-4">
    <i class="bi bi-cart3"></i> Panier d'Achat
</h1>

@if(count($cart) > 0)
    <div class="row">
        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Sous-total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/60x60?text={{ urlencode($item['name']) }}" 
                                             alt="{{ $item['name'] }}" 
                                             style="width: 60px; height: 60px; object-fit: cover; margin-right: 10px; border-radius: 5px;">
                                        <div>
                                            <strong>{{ $item['name'] }}</strong>
                                            <br>
                                            <small class="text-muted">ID: {{ $item['id'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($item['price'], 2) }} €</td>
                                <td>
                                    <form method="POST" action="{{ route('cart.update') }}" style="display: inline;">
                                        @csrf
                                        <input type="number" 
                                               name="quantity[{{ $item['id'] }}]" 
                                               value="{{ $item['quantity'] }}" 
                                               min="1" 
                                               class="form-control quantity-input" 
                                               onchange="this.form.submit()">
                                    </form>
                                </td>
                                <td>
                                    <strong>{{ number_format($item['price'] * $item['quantity'], 2) }} €</strong>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('cart.remove', $item['id']) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Continuer les achats
                </a>
                <form method="POST" action="{{ route('cart.clear') }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr?')">
                        <i class="bi bi-trash"></i> Vider le panier
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Résumé de la Commande</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Nombre d'articles:</span>
                        <strong>{{ $itemCount }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total:</span>
                        <strong>{{ number_format($total, 2) }} €</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Frais de port:</span>
                        <strong class="text-success">Gratuit</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h6 class="mb-0">Total:</h6>
                        <h5 class="price mb-0">{{ number_format($total, 2) }} €</h5>
                    </div>

                    @auth
                        <a href="{{ route('orders.checkout') }}" class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-credit-card"></i> Procéder au Paiement
                        </a>
                    @else
                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle"></i> 
                            <a href="{{ route('login') }}">Connectez-vous</a> ou 
                            <a href="{{ route('register') }}">inscrivez-vous</a> pour commander.
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Se Connecter
                        </a>
                    @endauth
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Informations</h6>
                </div>
                <div class="card-body small">
                    <p><i class="bi bi-shield-check"></i> Paiement sécurisé</p>
                    <p><i class="bi bi-truck"></i> Livraison rapide</p>
                    <p><i class="bi bi-arrow-counterclockwise"></i> Retours faciles</p>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-info">
        <h5><i class="bi bi-info-circle"></i> Votre panier est vide</h5>
        <p>Commencez vos achats en explorant nos produits.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="bi bi-shop"></i> Voir les Produits
        </a>
    </div>
@endif
@endsection
