@extends('layouts.app')

@section('title', 'Détails de la Commande #' . $order->id)

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Mes Commandes</a></li>
        <li class="breadcrumb-item active">Commande #{{ $order->id }}</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Commande #{{ $order->id }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted">Date de commande</p>
                        <p><strong>{{ $order->created_at->format('d/m/Y à H:i') }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted">Statut</p>
                        <p>
                            @switch($order->status)
                                @case('pending')
                                    <span class="badge bg-warning" style="font-size: 1rem;">En attente de paiement</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success" style="font-size: 1rem;">Commande confirmée</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger" style="font-size: 1rem;">Annulée</span>
                                    @break
                            @endswitch
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Articles Commandés</h5>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produit</th>
                            <th>Prix Unitaire</th>
                            <th>Quantité</th>
                            <th>Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('products.show', $item->product) }}">
                                        {{ $item->product->name }}
                                    </a>
                                </td>
                                <td>{{ number_format($item->price, 2) }} €</td>
                                <td>{{ $item->quantity }}</td>
                                <td><strong>{{ number_format($item->price * $item->quantity, 2) }} €</strong></td>
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
                <h5 class="mb-0">Résumé</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Sous-total:</span>
                    <strong>{{ number_format($order->total, 2) }} €</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Frais de port:</span>
                    <strong class="text-success">Gratuit</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <h6 class="mb-0">Total:</h6>
                    <h5 class="price mb-0">{{ number_format($order->total, 2) }} €</h5>
                </div>
            </div>
        </div>

        @if($order->status === 'pending')
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Paiement</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Le paiement de cette commande n'a pas encore été confirmé.</p>
                    <a href="{{ route('orders.payment', $order) }}" class="btn btn-primary w-100">
                        <i class="bi bi-credit-card"></i> Payer maintenant
                    </a>
                </div>
            </div>
        @elseif($order->status === 'completed')
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Paiement</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-success mb-0">
                        <i class="bi bi-check-circle"></i> Paiement confirmé
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Livraison</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Votre commande a été confirmée et sera bientôt préparée pour l'expédition.</p>
                    <p class="small"><i class="bi bi-info-circle"></i> Vous recevrez une notification dès que votre colis sera expédié.</p>
                </div>
            </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left"></i> Retour aux Commandes
            </a>
        </div>
    </div>
</div>
@endsection
