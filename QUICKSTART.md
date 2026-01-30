# Démarrage Rapide - Boutique en Ligne

## 🚀 Lancer l'Application en 30 Secondes

### Première Utilisation

```bash
cd projetLaravel

# Installer les dépendances
composer install
npm install

# Configurer la base de données
php artisan migrate:fresh --seed
npm run build

# Lancer le serveur
php artisan serve
```

Accédez à: **http://localhost:8000**

### Lancement Rapide (Après Installation)

```bash
# Terminal 1: Lancer le serveur
php artisan serve

# Terminal 2 (optionnel): Compiler les assets
npm run dev
```

## 🔐 Connexion

**Email**: `test@example.com`  
**Mot de passe**: `password`

## 📍 URLs Principales

- **Accueil**: http://localhost:8000/
- **Produits**: http://localhost:8000/products
- **Panier**: http://localhost:8000/cart
- **Connexion**: http://localhost:8000/login
- **Inscription**: http://localhost:8000/register
- **Mes Commandes**: http://localhost:8000/orders (après connexion)

## 🎯 Flux de Démonstration

### 1. Sans Connexion
- [ ] Accéder à la page d'accueil
- [ ] Parcourir les produits
- [ ] Ajouter des produits au panier
- [ ] Consulter le panier

### 2. Avec Connexion
- [ ] Se connecter avec test@example.com
- [ ] Ajouter des produits au panier
- [ ] Procéder au paiement
- [ ] Consulter l'historique des commandes

### 3. Recherche et Filtrage
- [ ] Utiliser la recherche pour trouver un produit
- [ ] Filtrer par catégorie
- [ ] Trier par prix

## 🛠️ Développement

### Compiler les Assets
```bash
# Production
npm run build

# Développement (avec watch)
npm run dev
```

### Exécuter les Migrations
```bash
# Créer les tables
php artisan migrate

# Réinitialiser
php artisan migrate:fresh

# Ajouter les données de test
php artisan db:seed
```

### Commandes Utiles
```bash
# Liste des routes
php artisan route:list

# Accéder à la console Tinker
php artisan tinker

# Vider le cache
php artisan cache:clear
```

## 📱 Fonctionnalités Testées

✅ Catalogue de produits  
✅ Panier d'achat avec sessions  
✅ Authentification  
✅ Création de commandes  
✅ Historique des commandes  
✅ Recherche et filtrage  
✅ Gestion du panier en temps réel  

## ⚠️ Notes

- Les images sont des placeholders (placeholder.com)
- Stripe est en mode test
- La base de données est SQLite (stockée dans `database/database.sqlite`)
- Les sessions sont stockées dans `storage/framework/sessions/`

## 🐛 Troubleshooting

### Le serveur ne démarre pas
```bash
# Générer une clé d'application
php artisan key:generate

# Vérifier les permissions
chmod -R 775 storage bootstrap/cache
```

### Erreur "419 Page Expired"
- Actualiser la page
- Vider les cookies du navigateur

### Le panier se vide
- C'est normal si vous fermez le navigateur (sessions par défaut)
- Configurer des sessions persistantes dans `.env`

## 📚 Ressources

- [Laravel Documentation](https://laravel.com/docs)
- [Bootstrap 5](https://getbootstrap.com/docs)
- [Blade Templates](https://laravel.com/docs/11.x/blade)

## 🎓 Structure à Explorer

```
app/
├── Http/Controllers/    # Contrôleurs
├── Models/              # Modèles Eloquent
└── Policies/            # Politiques d'autorisation

resources/views/
├── layouts/             # Layout principal
├── products/            # Vues produits
├── cart/                # Vue panier
└── orders/              # Vues commandes

database/
├── migrations/          # Schémas DB
└── seeders/             # Données de test
```

---

**Besoin d'aide?** Consultez `README.md` pour la documentation complète.
