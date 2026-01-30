@extends('layouts.app')

@section('title', 'Passer la Commande')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h1 class="mb-4">
            <i class="bi bi-clipboard-check"></i> Confirmation de Commande
        </h1>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Articles à Acheter</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item['name'] }}</strong>
                                </td>
                                <td>{{ number_format($item['price'], 2) }} €</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Résumé de la Commande</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Sous-total:</span>
                    <strong>{{ number_format($total, 2) }} €</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Frais de port:</span>
                    <strong class="text-success">Gratuit</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <h6 class="mb-0">Total:</h6>
                    <h5 class="price mb-0">{{ number_format($total, 2) }} €</h5>
                </div>

                <form method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100 btn-lg mb-2">
                        <i class="bi bi-check-circle"></i> Confirmer la Commande
                    </button>
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left"></i> Retour au Panier
                    </a>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Informations Client</h6>
            </div>
            <div class="card-body small">
                <p class="mb-2">
                    <strong>{{ Auth::user()->name }}</strong><br>
                    {{ Auth::user()->email }}
                </p>
                <p class="text-muted small mb-0">Vérifiez vos informations avant de confirmer.</p>
            </div>
        </div>
    </div>
</div>
@endsection
