# Configuration pour GitHub Copilot

## À Propos du Projet

Ceci est une **boutique en ligne complète** développée avec Laravel 11. Elle inclut:
- Un catalogue de produits avec catégories
- Un panier d'achat utilisant les sessions
- Un système d'authentification avec Breeze
- Un historique des commandes
- Une intégration Stripe (optionnelle)

## Structure Clé

### Modèles
- `Product` - Produits du catalogue
- `Category` - Catégories de produits
- `Order` - Commandes des utilisateurs
- `OrderItem` - Articles dans une commande
- `User` - Utilisateurs du système

### Contrôleurs
- `ProductController` - Gestion du catalogue et recherche
- `CartController` - Gestion du panier avec sessions
- `OrderController` - Gestion des commandes

### Vues Blade
- `layouts/app.blade.php` - Layout principal avec navigation
- `products/index.blade.php` - Catalogue avec filtrage
- `cart/index.blade.php` - Panier d'achat
- `orders/*` - Pages de commandes

## Commandes Importantes

```bash
# Lancer l'application
php artisan serve

# Réinitialiser la base de données
php artisan migrate:fresh --seed

# Compiler les assets
npm run build
```

## Points Clés du Développement

1. **Sessions**: Le panier stocke les articles en session
2. **Authentification**: Breeze fournit l'authentification
3. **Autorisations**: Vérification de l'utilisateur dans OrderController
4. **Relations**: Utilisateurs ↔ Commandes ↔ Articles
5. **Routes**: Routes protégées par middleware 'auth'

## Compte de Test

- Email: `test@example.com`
- Mot de passe: `password`

## Pour Poursuivre le Développement

- Intégrer Stripe API réelle
- Ajouter les notifications par email
- Créer un dashboard administrateur
- Ajouter des tests automatisés
- Implémenter un système de wishlist
