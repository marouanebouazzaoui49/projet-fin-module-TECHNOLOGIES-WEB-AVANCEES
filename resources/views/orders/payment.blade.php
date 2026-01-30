@extends('layouts.app')

@section('title', 'Paiement de la Commande #' . $order->id)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h1 class="mb-4 text-center">
            <i class="bi bi-credit-card"></i> Paiement
        </h1>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Commande #{{ $order->id }}</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Montant à payer:</span>
                    <h5 class="price mb-0">{{ number_format($order->total, 2) }} €</h5>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Détails du Paiement</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('orders.payment', $order) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="card_name" class="form-label">Nom sur la Carte</label>
                        <input type="text" class="form-control" id="card_name" name="card_name" placeholder="Jean Dupont" required>
                    </div>

                    <div class="mb-3">
                        <label for="card_number" class="form-label">Numéro de Carte</label>
                        <input type="text" class="form-control" id="card_number" name="card_number" placeholder="4242 4242 4242 4242" maxlength="19" required>
                        <small class="form-text text-muted">Pour les tests: 4242 4242 4242 4242</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="card_expiry" class="form-label">Date d'Expiration</label>
                                <input type="text" class="form-control" id="card_expiry" name="card_expiry" placeholder="MM/YY" maxlength="5" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="card_cvc" class="form-label">CVC</label>
                                <input type="text" class="form-control" id="card_cvc" name="card_cvc" placeholder="123" maxlength="3" required>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-shield-lock"></i>
                        <strong>Environnement de Test</strong>
                        <p class="mb-0 small">Vous êtes en environnement de test. Aucun paiement réel ne sera effectué.</p>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-lock"></i> Payer {{ number_format($order->total, 2) }} €
                    </button>

                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Sécurité</h6>
            </div>
            <div class="card-body small">
                <p class="mb-1"><i class="bi bi-check-circle-fill text-success"></i> Paiement sécurisé SSL</p>
                <p class="mb-1"><i class="bi bi-check-circle-fill text-success"></i> Vos données sont protégées</p>
                <p class="mb-0"><i class="bi bi-check-circle-fill text-success"></i> Aucune donnée n'est stockée</p>
            </div>
        </div>
    </div>
</div>
@endsection
