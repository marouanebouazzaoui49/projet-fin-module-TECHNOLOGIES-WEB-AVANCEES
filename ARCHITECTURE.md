# Architecture et Points d'Extension

## 🏗️ Architecture du Projet

### Couches de l'Application

```
┌─────────────────────────────────────┐
│      Presentation (Blade Views)     │
│  layouts/ | products/ | cart/orders/│
└─────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────┐
│    Routing & Controllers            │
│  HTTP/Controllers/*Controller.php   │
└─────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────┐
│      Business Logic (Models)        │
│  Models/* + Policies/*              │
└─────────────────────────────────────┘
                  ↓
┌─────────────────────────────────────┐
│    Data Layer (Eloquent ORM)        │
│  Migrations / Database              │
└─────────────────────────────────────┘
```

### Flux de Données

```
Utilisateur
    ↓
[Navigateur]
    ↓
Routes (routes/web.php)
    ↓
Contrôleurs (app/Http/Controllers/)
    ↓
Modèles (app/Models/)
    ↓
Base de Données (SQLite)
    ↓
Vues Blade (resources/views/)
    ↓
HTML/CSS/JS au Navigateur
```

---

## 📦 Structure de Fichiers

```
projetLaravel/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── ProductController.php      # Gestion catalogue
│   │       ├── CartController.php         # Gestion panier
│   │       └── OrderController.php        # Gestion commandes
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   └── OrderItem.php
│   └── Policies/
│       └── OrderPolicy.php                # Autorisations
│
├── database/
│   ├── migrations/
│   │   ├── 2026_01_30_*_create_products_table.php
│   │   ├── 2026_01_30_*_create_categories_table.php
│   │   ├── 2026_01_30_*_create_orders_table.php
│   │   └── 2026_01_30_*_create_order_items_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       └── ProductSeeder.php              # Données de test
│
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php                  # Layout principal
│   ├── products/
│   │   ├── index.blade.php                # Catalogue
│   │   └── show.blade.php                 # Détails produit
│   ├── cart/
│   │   └── index.blade.php                # Panier
│   ├── orders/
│   │   ├── index.blade.php                # Historique
│   │   ├── show.blade.php                 # Détails commande
│   │   ├── checkout.blade.php             # Confirmation
│   │   └── payment.blade.php              # Paiement
│   └── welcome.blade.php                  # Accueil
│
├── routes/
│   ├── web.php                            # Routes principales
│   └── auth.php                           # Routes Breeze
│
├── public/
│   └── build/                             # Assets compilés
│
├── storage/
│   ├── framework/
│   │   └── sessions/                      # Sessions de panier
│   └── logs/
│
├── config/
│   └── session.php                        # Configuration sessions
│
├── .env                                   # Variables d'environnement
├── README.md                              # Documentation complète
├── QUICKSTART.md                          # Démarrage rapide
├── STRIPE_INTEGRATION.md                  # Guide Stripe
└── API_ENDPOINTS.md                       # Endpoints disponibles
```

---

## 🔌 Points d'Extension

### 1. Ajouter une Nouvelle Catégorie de Produits

**Fichiers à modifier:**
- Aucun! Les catégories sont dynamiques en BDD

**Procédure:**
```php
// Depuis la console Tinker
php artisan tinker

> Category::create(['name' => 'Ma Catégorie', 'description' => '...']);
```

### 2. Ajouter des Filtres Avancés

**Fichiers à modifier:**
- `app/Http/Controllers/ProductController.php`
- `resources/views/products/index.blade.php`

**Exemple - Filtrer par Prix:**
```php
// ProductController.php
public function index()
{
    $minPrice = request('min_price', 0);
    $maxPrice = request('max_price', 10000);
    
    $products = Product::whereBetween('price', [$minPrice, $maxPrice])
        ->paginate(12);
    
    return view('products.index', compact('products'));
}
```

### 3. Système de Coupons/Réductions

**Fichiers à créer:**
- `app/Models/Coupon.php`
- `database/migrations/*_create_coupons_table.php`

**Champs suggérés:**
```php
Schema::create('coupons', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->enum('type', ['fixed', 'percentage']);
    $table->decimal('value', 10, 2);
    $table->timestamp('expires_at')->nullable();
    $table->timestamps();
});
```

### 4. Système d'Avis/Commentaires

**Fichiers à créer:**
- `app/Models/Review.php`
- `app/Http/Controllers/ReviewController.php`
- `database/migrations/*_create_reviews_table.php`

**Structure:**
```php
// reviews table
Schema::create('reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained();
    $table->foreignId('user_id')->constrained();
    $table->integer('rating'); // 1-5
    $table->text('comment');
    $table->timestamps();
});
```

### 5. Notifications par Email

**Fichiers à modifier:**
- `app/Http/Controllers/OrderController.php`

**Utiliser les Mails Laravel:**
```php
// Créer la mailable
php artisan make:mail OrderConfirmation

// Envoyer dans OrderController
Mail::to($user->email)->send(new OrderConfirmation($order));
```

### 6. Dashboard Administrateur

**Fichiers à créer:**
- `app/Http/Controllers/Admin/DashboardController.php`
- `resources/views/admin/`

**Authentification Admin:**
```php
// Dans la Policy ou Middleware
if ($user->role !== 'admin') {
    abort(403);
}
```

### 7. API REST pour Mobile

**Utiliser les Resource Controllers de Laravel:**
```bash
php artisan make:controller Api/ProductController --api
```

**Routes:**
```php
Route::apiResource('products', Api\ProductController::class);
Route::apiResource('orders', Api\OrderController::class);
```

### 8. Wishlist/Favoris

**Fichiers à créer:**
- `app/Models/Wishlist.php`
- Pivot table: `user_wishlist_product`

**Relation Many-to-Many:**
```php
public function wishlist()
{
    return $this->belongsToMany(Product::class, 'wishlist');
}
```

### 9. Système de Recommandations

**Approche Simple - Produits Populaires:**
```php
$popular = Product::withCount('orderItems')
    ->orderByDesc('order_items_count')
    ->limit(5)
    ->get();
```

### 10. Panier Persistant en Base de Données

**Au lieu de Sessions:**
```php
// Créer une table carts
Schema::create('carts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->foreignId('product_id')->constrained();
    $table->integer('quantity');
    $table->timestamps();
});

// Modifier CartController
```

---

## 🚀 Optimisations Possibles

### Performance

1. **Cache des Produits Populaires**
   ```php
   Cache::remember('popular_products', 60*60, function() {
       return Product::withCount('orderItems')->limit(10)->get();
   });
   ```

2. **Pagination Lazy Loading**
   ```php
   $products->load('category'); // Eager loading
   ```

3. **Pagination Curseur pour de Gros Volumes**
   ```php
   Product::cursorPaginate(12);
   ```

### Sécurité

1. **Rate Limiting**
   ```php
   Route::post('/orders', [OrderController::class, 'store'])
       ->middleware('throttle:10,1'); // 10 commandes par minute
   ```

2. **CORS pour API**
   ```php
   header('Access-Control-Allow-Origin: *');
   ```

3. **Validation Côté Serveur Renforcée**
   ```php
   $validated = $request->validate([
       'quantity' => 'required|integer|min:1|max:100',
   ]);
   ```

---

## 🧪 Tests Automatisés

### Tests Unitaires

```bash
php artisan make:test ProductTest
```

```php
public function test_can_get_product()
{
    $product = Product::factory()->create();
    
    $response = $this->get("/products/{$product->id}");
    $response->assertStatus(200);
}
```

### Tests d'Intégration

```bash
php artisan make:test CartIntegrationTest
```

### Tests de Feature

```bash
php artisan make:test CheckoutTest
```

---

## 🔄 Cycle de Développement

### Ajouter une Nouvelle Fonctionnalité

1. **Créer la Migration**
   ```bash
   php artisan make:migration add_feature_to_table
   ```

2. **Créer le Modèle**
   ```bash
   php artisan make:model Feature
   ```

3. **Créer le Contrôleur**
   ```bash
   php artisan make:controller FeatureController
   ```

4. **Ajouter les Routes**
   ```php
   Route::resource('features', FeatureController::class);
   ```

5. **Créer les Vues**
   - `resources/views/features/index.blade.php`
   - `resources/views/features/show.blade.php`
   - `resources/views/features/create.blade.php`
   - `resources/views/features/edit.blade.php`

6. **Tester**
   ```bash
   php artisan serve
   ```

---

## 📚 Ressources d'Apprentissage

- [Laravel Documentation](https://laravel.com/docs/11.x)
- [Eloquent ORM](https://laravel.com/docs/11.x/eloquent)
- [Blade Templates](https://laravel.com/docs/11.x/blade)
- [Controllers](https://laravel.com/docs/11.x/controllers)
- [Routing](https://laravel.com/docs/11.x/routing)
- [Migrations](https://laravel.com/docs/11.x/migrations)

---

## 🎯 Prochaines Étapes Recommandées

1. ✅ Implémenter Stripe réel
2. ✅ Ajouter tests automatisés
3. ✅ Mettre en cache les catégories
4. ✅ Ajouter système de notes/avis
5. ✅ Créer un dashboard administrateur
6. ✅ Implémenter notifications par email
7. ✅ Ajouter système de coupons
8. ✅ Créer une API REST
9. ✅ Ajouter wishlist
10. ✅ Mettre en place Analytics

---

**Dernière mise à jour:** 30 Janvier 2026
