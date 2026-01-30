# Intégration Stripe (Optionnel)

## 📌 Vue d'Ensemble

Ce guide explique comment intégrer Stripe pour les paiements réels dans la boutique. L'application est actuellement en mode test avec des paiements simulés.

## 🔧 Installation

### 1. Installer le Package Stripe

```bash
composer require stripe/stripe-php
```

### 2. Créer un Compte Stripe

1. Aller sur [https://stripe.com](https://stripe.com)
2. Créer un compte ou se connecter
3. Récupérer les clés API:
   - **Publishable Key** (clé publique)
   - **Secret Key** (clé secrète)

### 3. Configurer les Clés API

Ajouter au fichier `.env`:

```env
STRIPE_PUBLIC_KEY=pk_test_YOUR_KEY_HERE
STRIPE_SECRET_KEY=sk_test_YOUR_KEY_HERE
```

### 4. Installer Stripe CLI (Optionnel)

Pour tester les webhooks localement:

```bash
# Windows
choco install stripe-cli
# ou télécharger depuis https://stripe.com/docs/stripe-cli

# macOS
brew install stripe/stripe-cli/stripe

# Linux
curl -s https://packages.stripe.dev/api/v1/repos/stripe-cli/releases/latest/downloads/linux/x86_64.tar.gz | tar xz -C ~/Downloads
```

## 📝 Implémentation

### 1. Créer une Migration pour Stripe

```bash
php artisan make:migration add_stripe_fields_to_orders --table=orders
```

Ajouter les colonnes:

```php
$table->string('stripe_session_id')->nullable();
$table->string('stripe_payment_intent_id')->nullable();
$table->timestamp('payment_confirmed_at')->nullable();
```

### 2. Modifier le Contrôleur des Commandes

Exemple simplifié (voir `app/Http/Controllers/OrderController.php`):

```php
<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        
        $cart = session('cart', []);
        
        // Préparer les articles pour Stripe
        $lineItems = [];
        foreach ($cart as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => $item['price'] * 100, // Stripe utilise les centimes
                ],
                'quantity' => $item['quantity'],
            ];
        }
        
        // Créer une session Stripe
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('orders.success'),
            'cancel_url' => route('cart.index'),
        ]);
        
        return redirect($session->url);
    }
    
    public function success(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        
        $sessionId = $request->get('session_id');
        $session = Session::retrieve($sessionId);
        
        // Créer la commande
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $session->amount_total / 100,
            'status' => 'completed',
            'stripe_session_id' => $sessionId,
        ]);
        
        // Vider le panier
        session()->forget('cart');
        
        return redirect()->route('orders.show', $order)
            ->with('success', 'Paiement effectué avec succès!');
    }
}
```

### 3. Mettre à Jour les Routes

```php
Route::post('/checkout', [OrderController::class, 'checkout'])
    ->middleware('auth')
    ->name('orders.checkout');

Route::get('/checkout/success', [OrderController::class, 'success'])
    ->middleware('auth')
    ->name('orders.success');
```

### 4. Mettre à Jour la Vue de Paiement

Remplacer le formulaire simple par le checkout Stripe:

```blade
<form method="POST" action="{{ route('orders.checkout') }}">
    @csrf
    <button type="submit" class="btn btn-primary btn-lg w-100">
        <i class="bi bi-credit-card"></i> Payer avec Stripe
    </button>
</form>
```

## 🧪 Test en Environnement Sandbox

### Numéros de Carte de Test

| Carte | Numéro | Expiration | CVC |
|-------|--------|------------|-----|
| Visa | 4242 4242 4242 4242 | 12/25 | 123 |
| Visa (Décline) | 4000 0000 0000 0002 | 12/25 | 123 |
| Mastercard | 5555 5555 5555 4444 | 12/25 | 123 |

### Tester en Local avec Stripe CLI

```bash
# Vérifier l'installation
stripe --version

# Se connecter à Stripe
stripe login

# Forwarder les webhooks localement
stripe listen --forward-to localhost:8000/webhooks/stripe

# Voir les événements
stripe trigger payment_intent.succeeded
```

## 🔔 Webhooks (Optionnel)

Pour gérer les événements Stripe (ex: paiement confirmé):

```php
// routes/web.php
Route::post('/webhooks/stripe', [WebhookController::class, 'handleStripe']);

// app/Http/Controllers/WebhookController.php
<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Webhook;

class WebhookController extends Controller
{
    public function handleStripe()
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        
        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        
        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                env('STRIPE_WEBHOOK_SECRET')
            );
        } catch (\Exception $e) {
            return response('Webhook error', 400);
        }
        
        // Gérer les événements
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSuccess($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
        }
        
        return response('OK', 200);
    }
    
    private function handlePaymentSuccess($paymentIntent)
    {
        // Marquer la commande comme payée
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        if ($order) {
            $order->update(['status' => 'completed']);
        }
    }
}
```

## 🔐 Sécurité

### Recommandations Importantes

1. **Ne jamais exposer la clé secrète**
   - Stocker dans `.env` (non versionné)
   - Utiliser `env()` pour y accéder

2. **Valider les paiements côté serveur**
   - Ne pas faire confiance aux données du client
   - Vérifier avec les webhooks

3. **Utiliser des secrets d'environnement**
   ```bash
   # .env (ne pas commiter)
   STRIPE_SECRET_KEY=sk_test_...
   STRIPE_PUBLIC_KEY=pk_test_...
   ```

4. **Réduire les informations sensibles**
   - Ne pas stocker les numéros de carte complets
   - Utiliser les Payment Methods de Stripe

## 📊 Monitoring

### Vérifier les Transactions

1. Accéder au [Dashboard Stripe](https://dashboard.stripe.com)
2. Aller dans "Paiements"
3. Voir l'historique des transactions

### Logs

Les erreurs Stripe sont loggées dans `storage/logs/laravel.log`

## 🐛 Dépannage

### Session Stripe invalide
- Vérifier les clés API
- S'assurer que le montant est > 50 centimes
- Vérifier la devise

### Erreur 401 Non autorisé
```bash
# Régénérer les clés API dans le dashboard Stripe
```

### Les webhooks ne déclenchent pas
```bash
# Vérifier la configuration du endpoint
# S'assurer que le serveur est accessible

# En développement, utiliser Stripe CLI:
stripe listen --forward-to localhost:8000/webhooks/stripe
```

## 📚 Ressources

- [Documentation Stripe PHP](https://stripe.com/docs/libraries/php)
- [Checkout Stripe](https://stripe.com/docs/payments/checkout)
- [Webhooks Stripe](https://stripe.com/docs/webhooks)
- [Test des Cartes](https://stripe.com/docs/testing)

## ✅ Checklist Implémentation

- [ ] Installer le package Stripe
- [ ] Créer un compte Stripe
- [ ] Ajouter les clés API à `.env`
- [ ] Modifier OrderController
- [ ] Tester avec les cartes de test
- [ ] Configurer les webhooks
- [ ] Déployer en production
- [ ] Mettre à jour les clés en production

---

**Note**: En production, utiliser les clés réelles (pk_live_... et sk_live_...)
