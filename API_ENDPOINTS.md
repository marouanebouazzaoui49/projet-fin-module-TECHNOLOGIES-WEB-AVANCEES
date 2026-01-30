# API Endpoints - Boutique en Ligne

## 🔗 Endpoints Disponibles

### 📄 Pages Publiques

#### Accueil
```
GET /
```
Affiche la page d'accueil de la boutique.

#### Catalogue de Produits
```
GET /products
```
Affiche la liste paginée de tous les produits (12 par page).

**Paramètres de pagination:**
- `?page=1` - Numéro de page

**Réponse:** Vue Blade avec liste de produits

#### Détails d'un Produit
```
GET /products/{product}
```
Affiche les détails complets d'un produit et les produits associés.

**Paramètres:**
- `{product}` - ID du produit (ex: `/products/1`)

**Réponse:** Vue Blade avec détails du produit

#### Produits par Catégorie
```
GET /category/{category}
```
Affiche tous les produits d'une catégorie spécifique.

**Paramètres:**
- `{category}` - ID de la catégorie (ex: `/category/1`)

**Réponse:** Vue Blade avec produits filtrés

#### Recherche de Produits
```
GET /search?q=terme
```
Recherche les produits par nom ou description.

**Paramètres de query:**
- `q` - Terme de recherche (obligatoire)
- `page` - Numéro de page (optionnel)

**Exemple:** `/search?q=laptop&page=1`

**Réponse:** Vue Blade avec résultats de recherche

---

### 🛒 Endpoints Panier (Sessions)

#### Afficher le Panier
```
GET /cart
```
Affiche le contenu du panier actuel.

**Authentification:** Aucune requise

**Réponse:** Vue Blade avec détails du panier

#### Ajouter au Panier
```
POST /cart/add/{product}
```
Ajoute un produit au panier de la session.

**Paramètres:**
- `{product}` - ID du produit

**Body (application/x-www-form-urlencoded):**
```
quantity=1
_token=CSRF_TOKEN
```

**Réponse:** Redirection avec message de succès

#### Mettre à Jour les Quantités
```
POST /cart/update
```
Met à jour les quantités de tous les articles du panier.

**Body (application/x-www-form-urlencoded):**
```
quantity[1]=2
quantity[2]=3
_token=CSRF_TOKEN
```

**Réponse:** Redirection avec message de succès

#### Supprimer du Panier
```
DELETE /cart/remove/{productId}
```
Supprime un produit spécifique du panier.

**Paramètres:**
- `{productId}` - ID du produit à supprimer

**Body:**
```
_method=DELETE
_token=CSRF_TOKEN
```

**Réponse:** Redirection avec message de succès

#### Vider le Panier
```
DELETE /cart/clear
```
Vide complètement le panier.

**Body:**
```
_method=DELETE
_token=CSRF_TOKEN
```

**Réponse:** Redirection avec message de succès

#### Récupérer le Panier (JSON)
```
GET /api/cart
```
Retourne le contenu du panier au format JSON.

**Authentification:** Aucune requise

**Réponse JSON:**
```json
{
  "cart": {
    "1": {
      "id": 1,
      "name": "Produit 1",
      "price": 29.99,
      "quantity": 2,
      "image": "product.jpg"
    }
  },
  "itemCount": 2,
  "total": 59.98
}
```

---

### 🔐 Endpoints Commandes (Authentification Requise)

#### Lister Mes Commandes
```
GET /orders
```
Affiche l'historique des commandes de l'utilisateur connecté.

**Authentification:** Requise (middleware: auth)

**Réponse:** Vue Blade avec liste paginée des commandes

#### Détails d'une Commande
```
GET /orders/{order}
```
Affiche les détails complets d'une commande (vérification d'ownership).

**Authentification:** Requise

**Paramètres:**
- `{order}` - ID de la commande

**Réponse:** Vue Blade avec détails de la commande

**Erreur possible:**
- `403 Forbidden` - Si l'utilisateur n'est pas propriétaire de la commande

#### Page de Confirmation
```
GET /checkout
```
Affiche la page de confirmation avant de créer la commande.

**Authentification:** Requise

**Conditions:**
- Le panier ne doit pas être vide

**Réponse:** Vue Blade de confirmation

#### Créer une Commande
```
POST /orders
```
Crée une nouvelle commande à partir du panier.

**Authentification:** Requise

**Body:**
```
_token=CSRF_TOKEN
```

**Réponse:** Redirection vers les détails de la commande

**Erreurs possibles:**
- `302` avec message d'erreur si le panier est vide

#### Page de Paiement
```
GET /orders/{order}/payment
```
Affiche la page de paiement pour une commande.

**Authentification:** Requise

**Paramètres:**
- `{order}` - ID de la commande

**Réponse:** Vue Blade avec formulaire de paiement

#### Traiter le Paiement
```
POST /orders/{order}/payment
```
Traite le paiement de la commande (mode test ou Stripe).

**Authentification:** Requise

**Paramètres:**
- `{order}` - ID de la commande

**Body:**
```
card_name=John Doe
card_number=4242 4242 4242 4242
card_expiry=12/25
card_cvc=123
_token=CSRF_TOKEN
```

**Réponse:** Redirection vers détails de la commande avec succès

---

### 🔐 Endpoints Authentification

#### Page de Connexion
```
GET /login
```
Affiche le formulaire de connexion.

#### Connexion
```
POST /login
```
Authentifie l'utilisateur.

**Body:**
```
email=test@example.com
password=password
_token=CSRF_TOKEN
remember=on
```

#### Page d'Inscription
```
GET /register
```
Affiche le formulaire d'inscription.

#### Inscription
```
POST /register
```
Crée un nouvel utilisateur.

**Body:**
```
name=Nom Utilisateur
email=email@example.com
password=password
password_confirmation=password
_token=CSRF_TOKEN
```

#### Déconnexion
```
POST /logout
```
Déconnecte l'utilisateur connecté.

**Body:**
```
_token=CSRF_TOKEN
```

---

## 📊 Modèles de Données

### Product
```json
{
  "id": 1,
  "category_id": 1,
  "name": "Produit",
  "description": "Description...",
  "price": 29.99,
  "stock": 50,
  "image": "image.jpg",
  "created_at": "2026-01-30T...",
  "updated_at": "2026-01-30T..."
}
```

### Category
```json
{
  "id": 1,
  "name": "Électroniques",
  "description": "Description...",
  "created_at": "2026-01-30T...",
  "updated_at": "2026-01-30T..."
}
```

### Order
```json
{
  "id": 1,
  "user_id": 1,
  "total": 99.99,
  "status": "pending|completed|cancelled",
  "stripe_payment_id": "mock_123456",
  "created_at": "2026-01-30T...",
  "updated_at": "2026-01-30T..."
}
```

### OrderItem
```json
{
  "id": 1,
  "order_id": 1,
  "product_id": 1,
  "quantity": 2,
  "price": 29.99,
  "created_at": "2026-01-30T...",
  "updated_at": "2026-01-30T..."
}
```

### Cart Item (Session)
```json
{
  "id": 1,
  "name": "Produit",
  "price": 29.99,
  "quantity": 2,
  "image": "image.jpg"
}
```

---

## 🔒 Authentification

### État Non Authentifié
- Accès au catalogue et panier
- Impossible de commander
- Redirection vers login lors du checkout

### État Authentifié
- Accès aux commandes
- Paiement des commandes
- Gestion du profil

---

## 🔄 Code de Statut HTTP

| Code | Signification |
|------|---------------|
| 200 | OK - Succès |
| 302 | Redirect - Redirection |
| 403 | Forbidden - Accès refusé |
| 404 | Not Found - Ressource non trouvée |
| 419 | Token Expired - Token CSRF expiré |
| 500 | Server Error - Erreur serveur |

---

## 📝 Exemples cURL

### Ajouter au Panier
```bash
curl -X POST http://localhost:8000/cart/add/1 \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "quantity=1&_token=CSRF_TOKEN"
```

### Récupérer le Panier (JSON)
```bash
curl -X GET http://localhost:8000/api/cart \
  -H "Accept: application/json"
```

### Chercher un Produit
```bash
curl -X GET "http://localhost:8000/search?q=laptop"
```

### Lister Mes Commandes
```bash
curl -X GET http://localhost:8000/orders \
  -H "Cookie: LARAVEL_SESSION=..."
```

---

**Note**: Tous les endpoints POST/DELETE requièrent un token CSRF valide.
