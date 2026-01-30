# Cas d'Usage Avancés - Exemples de Code

## 🎯 Cas d'Usage 1: Ajouter une Promotion Saisonnière

### Objectif
Afficher un badge "En Promotion" sur certains produits avec une réduction.

### Implémentation

**1. Migration**
```bash
php artisan make:migration add_discount_to_products --table=products
```

```php
Schema::table('products', function (Blueprint $table) {
    $table->decimal('discount_price', 10, 2)->nullable();
    $table->string('discount_label')->nullable();
});
```

**2. Modèle**
```php
// app/Models/Product.php
public function getDiscountPercentageAttribute()
{
    if (!$this->discount_price) return null;
    
    $percentage = (($this->price - $this->discount_price) / $this->price) * 100;
    return round($percentage);
}

public function getSalePriceAttribute()
{
    return $this->discount_price ?? $this->price;
}
```

**3. Vue**
```blade
<!-- resources/views/products/index.blade.php -->
<div class="product-card">
    @if($product->discount_price)
        <span class="badge bg-danger position-absolute top-0 end-0">
            -{{ $product->discount_percentage }}%
        </span>
    @endif
    
    <p class="price">
        @if($product->discount_price)
            <del class="text-muted">{{ $product->price }}€</del>
            <strong class="text-danger">{{ $product->sale_price }}€</strong>
        @else
            {{ $product->price }}€
        @endif
    </p>
</div>
```

---

## 🎯 Cas d'Usage 2: Système de Wishlist

### Objectif
Permettre aux utilisateurs de sauvegarder leurs produits favoris.

### Implémentation

**1. Modèle Wishlist**
```bash
php artisan make:model Wishlist -m
```

```php
// Migration
Schema::create('wishlists', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    $table->unique(['user_id', 'product_id']);
});
```

**2. Relation dans User**
```php
// app/Models/User.php
public function wishlist()
{
    return $this->hasMany(Wishlist::class);
}

public function wishlistProducts()
{
    return $this->belongsToMany(Product::class, 'wishlists');
}
```

**3. Contrôleur**
```php
// app/Http/Controllers/WishlistController.php
namespace App\Http\Controllers;

use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function toggle(Product $product)
    {
        $exists = Auth::user()->wishlist()
            ->where('product_id', $product->id)
            ->exists();
        
        if ($exists) {
            Auth::user()->wishlist()
                ->where('product_id', $product->id)
                ->delete();
            $message = 'Retiré des favoris';
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);
            $message = 'Ajouté aux favoris';
        }
        
        return back()->with('success', $message);
    }
    
    public function index()
    {
        $products = Auth::user()->wishlistProducts()->paginate(12);
        return view('wishlist.index', compact('products'));
    }
}
```

**4. Routes**
```php
// routes/web.php
Route::middleware('auth')->group(function () {
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
});
```

---

## 🎯 Cas d'Usage 3: Système de Coupons/Codes Promo

### Objectif
Appliquer des réductions avec des codes promo.

### Implémentation

**1. Modèle Coupon**
```bash
php artisan make:model Coupon -m
```

```php
// Migration
Schema::create('coupons', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->enum('type', ['fixed', 'percentage']);
    $table->decimal('value', 10, 2);
    $table->integer('usage_limit')->nullable();
    $table->integer('used')->default(0);
    $table->timestamp('expires_at')->nullable();
    $table->boolean('active')->default(true);
    $table->timestamps();
});
```

**2. Contrôleur**
```php
// app/Http/Controllers/CouponController.php
public function apply(Request $request)
{
    $validated = $request->validate([
        'coupon_code' => 'required|string',
    ]);
    
    $coupon = Coupon::where('code', $validated['coupon_code'])
        ->where('active', true)
        ->first();
    
    if (!$coupon) {
        return back()->withErrors(['coupon' => 'Code invalide']);
    }
    
    if ($coupon->expires_at && $coupon->expires_at < now()) {
        return back()->withErrors(['coupon' => 'Code expiré']);
    }
    
    if ($coupon->usage_limit && $coupon->used >= $coupon->usage_limit) {
        return back()->withErrors(['coupon' => 'Code utilisé trop de fois']);
    }
    
    session(['coupon' => $coupon->id]);
    return back()->with('success', 'Code promo appliqué!');
}

public function calculateDiscount($total)
{
    $coupon = Coupon::find(session('coupon'));
    if (!$coupon) return 0;
    
    if ($coupon->type === 'fixed') {
        return $coupon->value;
    } else {
        return ($total * $coupon->value) / 100;
    }
}
```

**3. Vue**
```blade
<!-- resources/views/cart/coupon.blade.php -->
<form method="POST" action="{{ route('coupon.apply') }}">
    @csrf
    <div class="input-group">
        <input type="text" name="coupon_code" class="form-control" 
               placeholder="Code promo" value="{{ session('coupon_code') }}">
        <button class="btn btn-outline-secondary" type="submit">Appliquer</button>
    </div>
</form>

@if(session('coupon'))
    <p class="text-success">Réduction: -{{ $discount }}€</p>
@endif
```

---

## 🎯 Cas d'Usage 4: Notifications par Email

### Objectif
Envoyer des emails lors de commandes.

### Implémentation

**1. Créer la Mailable**
```bash
php artisan make:mail OrderConfirmation
```

```php
// app/Mail/OrderConfirmation.php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class OrderConfirmation extends Mailable
{
    public function __construct(public Order $order) {}
    
    public function envelope()
    {
        return new Envelope(
            subject: "Confirmation de votre commande #" . $this->order->id,
        );
    }
    
    public function content()
    {
        return new Content(
            view: 'emails.order-confirmation',
        );
    }
}
```

**2. Vue Email**
```blade
<!-- resources/views/emails/order-confirmation.blade.php -->
<h1>Commande Confirmée!</h1>
<p>Merci pour votre commande #{{ $order->id }}</p>

<h3>Détails:</h3>
@foreach($order->items as $item)
    <p>
        {{ $item->product->name }} x{{ $item->quantity }} = 
        {{ number_format($item->price * $item->quantity, 2) }}€
    </p>
@endforeach

<p><strong>Total: {{ number_format($order->total, 2) }}€</strong></p>
```

**3. Envoyer l'Email**
```php
// app/Http/Controllers/OrderController.php
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;

public function store(Request $request)
{
    // ... créer la commande ...
    
    Mail::to(Auth::user()->email)->send(new OrderConfirmation($order));
    
    return redirect()->route('orders.show', $order);
}
```

---

## 🎯 Cas d'Usage 5: Dashboard Administrateur

### Objectif
Créer une interface pour gérer les produits.

### Implémentation

**1. Contrôleur Admin**
```bash
php artisan make:controller Admin/ProductController --model=Product
```

**2. Routes Admin**
```php
// routes/web.php
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('products', Admin\ProductController::class);
    Route::resource('categories', Admin\CategoryController::class);
    Route::resource('orders', Admin\OrderController::class);
});
```

**3. Middleware Admin**
```bash
php artisan make:middleware IsAdmin
```

```php
// app/Http/Middleware/IsAdmin.php
public function handle($request, Closure $next)
{
    if (Auth::user() && Auth::user()->role === 'admin') {
        return $next($request);
    }
    
    abort(403, 'Non autorisé');
}
```

**4. Vue Admin**
```blade
<!-- resources/views/admin/products/index.blade.php -->
<div class="container mt-4">
    <h1>Gestion des Produits</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        Ajouter un produit
    </a>
    
    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->price }}€</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">
                            Éditer
                        </a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Sûr?')">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
```

---

## 🎯 Cas d'Usage 6: Système de Recommandations

### Objectif
Suggérer des produits similaires ou populaires.

### Implémentation

**1. Contrôleur**
```php
// app/Http/Controllers/RecommendationController.php
class RecommendationController extends Controller
{
    public function similar(Product $product)
    {
        // Produits de la même catégorie
        $similar = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();
        
        return $similar;
    }
    
    public function popular()
    {
        // Produits les plus commandés
        return Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(4)
            ->get();
    }
    
    public function forYou()
    {
        // Basé sur l'historique d'achat
        $userCategories = Auth::user()->orders()
            ->with('items.product')
            ->get()
            ->pluck('items.*.product.category_id')
            ->flatten()
            ->unique();
        
        return Product::whereIn('category_id', $userCategories)
            ->limit(4)
            ->get();
    }
}
```

**2. Routes**
```php
Route::get('/api/recommendations/similar/{product}', 'RecommendationController@similar');
Route::get('/api/recommendations/popular', 'RecommendationController@popular');
Route::get('/api/recommendations/for-you', 'RecommendationController@forYou')->middleware('auth');
```

---

## 🎯 Cas d'Usage 7: Analytics et Reporting

### Objectif
Suivre les ventes et les tendances.

### Implémentation

**1. Dashboard Analytics**
```php
// app/Http/Controllers/AnalyticsController.php
class AnalyticsController extends Controller
{
    public function index()
    {
        $totalSales = Order::where('status', 'completed')->sum('total');
        $totalOrders = Order::where('status', 'completed')->count();
        $totalProducts = Product::count();
        
        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total) as revenue')
            ->where('status', 'completed')
            ->groupBy('month')
            ->get();
        
        $topProducts = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->limit(10)
            ->get();
        
        return view('admin.analytics', compact(
            'totalSales',
            'totalOrders',
            'totalProducts',
            'monthlyRevenue',
            'topProducts'
        ));
    }
}
```

**2. Vue avec Graphique (Chart.js)**
```blade
<!-- resources/views/admin/analytics.blade.php -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<canvas id="revenueChart"></canvas>

<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin'],
        datasets: [{
            label: 'Revenus',
            data: @json($monthlyRevenue->pluck('revenue')),
            borderColor: 'rgb(75, 192, 192)',
        }]
    }
});
</script>
```

---

## 🎯 Cas d'Usage 8: API REST pour Mobile

### Objectif
Créer une API pour une application mobile.

### Implémentation

**1. Routes API**
```php
// routes/api.php
Route::apiResource('products', Api\ProductController::class)->only(['index', 'show']);
Route::apiResource('categories', Api\CategoryController::class)->only(['index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('orders', Api\OrderController::class);
    Route::post('/cart/add', [Api\CartController::class, 'add']);
    Route::post('/cart/checkout', [Api\CartController::class, 'checkout']);
});
```

**2. Contrôleur API**
```php
// app/Http/Controllers/Api/ProductController.php
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(12);
        return ProductResource::collection($products);
    }
    
    public function show(Product $product)
    {
        return ProductResource::make($product);
    }
}
```

**3. Resource**
```php
// app/Http/Resources/ProductResource.php
class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'stock' => $this->stock,
            'category' => $this->category->name,
        ];
    }
}
```

---

## 🧠 Tips & Tricks

### Performance
- Utiliser `select()` pour limiter les colonnes
- Implémenter le eager loading avec `with()`
- Mettre en cache les données statiques

### Sécurité
- Valider toutes les entrées
- Utiliser les policies Laravel
- Protéger avec des rates limits

### Tests
- Tester les contrôleurs
- Tester les modèles
- Tester les APIs

---

**Bon développement! 🚀**
