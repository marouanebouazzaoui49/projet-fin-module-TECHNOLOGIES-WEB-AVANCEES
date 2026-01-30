@extends('layouts.app')

@section('title', 'Mes Commandes')

@section('content')
<h1 class="mb-4">
    <i class="bi bi-bag"></i> Mes Commandes
</h1>

@if($orders->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>N° Commande</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Total</th>
                    <th>Articles</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @switch($order->status)
                                @case('pending')
                                    <span class="badge bg-warning">En attente</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success">Complétée</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger">Annulée</span>
                                    @break
                            @endswitch
                        </td>
                        <td><strong>{{ number_format($order->total, 2) }} €</strong></td>
                        <td>{{ $order->items->sum('quantity') }} article(s)</td>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> Détails
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        {{ $orders->links() }}
    </nav>
@else
    <div class="alert alert-info">
        <h5><i class="bi bi-info-circle"></i> Aucune commande</h5>
        <p>Vous n'avez pas encore passé de commande. Commencez vos achats dès maintenant!</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="bi bi-shop"></i> Découvrir nos Produits
        </a>
    </div>
@endif
@endsection
