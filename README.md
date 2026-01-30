# Boutique en Ligne Laravel

Une application e-commerce moderne développée avec Laravel 11, proposant un catalogue de produits, un panier d'achat avec sessions, l'historique des commandes et une intégration Stripe optionnelle.

## 🎯 Fonctionnalités

### ✅ Implémentées

- **Catalogue de Produits**: Affichage complet des produits avec catégorisation
- **Panier d'Achat**: Gestion du panier via sessions Laravel
- **Authentification**: Système d'authentification avec Laravel Breeze
- **Historique des Commandes**: Suivi des commandes pour les utilisateurs authentifiés
- **Recherche et Filtrage**: Recherche par nom/description et filtrage par catégorie
- **Design Responsive**: Interface Bootstrap 5 responsive et moderne
- **Système de Paiement**: Page de paiement avec interface (Stripe ready)

## 🚀 Technologies Utilisées

- **Backend**: Laravel 11
- **Frontend**: Blade Templates, Bootstrap 5
- **Base de Données**: SQLite (configurable)
- **Authentification**: Laravel Breeze
- **Sessions**: Gestion PHP native
- **Paiement**: Stripe API (optionnel)

## 📦 Installation

### Prérequis
- PHP 8.2+
- Composer
- Node.js & npm

### Étapes d'Installation

```bash
# 1. Accéder au répertoire
cd projetLaravel

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances Node.js
npm install

# 4. Générer la clé d'application
php artisan key:generate

# 5. Exécuter les migrations
php artisan migrate

# 6. Remplir la base de données avec des données de test
php artisan db:seed

# 7. Compiler les assets
npm run build

# 8. Lancer le serveur
php artisan serve
```

L'application sera accessible à `http://localhost:8000`

## 📁 Structure du Projet

```
resources/
├── views/
│   ├── layouts/app.blade.php          # Layout principal
│   ├── products/
│   │   ├── index.blade.php            # Catalogue
│   │   └── show.blade.php             # Détails produit
│   ├── cart/index.blade.php           # Panier
│   └── orders/
│       ├── index.blade.php            # Historique
│       ├── show.blade.php             # Détails commande
│       ├── checkout.blade.php         # Confirmation
│       └── payment.blade.php          # Paiement

app/Http/Controllers/
├── ProductController.php
├── CartController.php
└── OrderController.php
```

## 🛒 Utilisation

### Comptes de Test

Email: `test@example.com`  
Mot de passe: `password`

### Flux d'Achat

1. Naviguer vers `/products`
2. Ajouter des produits au panier
3. Accéder à `/cart` pour revoir le panier
4. Connexion requise pour commander
5. Finaliser la commande et procéder au paiement

## 🔒 Sécurité

- Protection CSRF sur tous les formulaires
- Policies d'autorisation Laravel
- Authentification obligatoire pour les commandes
- Validation côté serveur
- Hachage des mots de passe avec bcrypt

## 🛠️ Commandes Utiles

```bash
php artisan serve              # Lancer le serveur
php artisan migrate            # Exécuter les migrations
php artisan db:seed            # Remplir la base de données
php artisan migrate:fresh --seed # Réinitialiser complet
npm run build                  # Compiler les assets
```

## 📚 Routes Principales

- `GET /` - Accueil
- `GET /products` - Catalogue
- `GET /cart` - Panier
- `GET /orders` - Mes commandes (authentifié)
- `GET /login` - Connexion
- `GET /register` - Inscription

## 🎓 Apprentissages Clés

Ce projet démontre:
- Gestion des sessions Laravel pour le panier
- Authentification avec Breeze
- Relations Eloquent (HasMany, BelongsTo)
- Policies d'autorisation
- Validation de formulaires
- Paginationdes résultats
- Construction de vues avec Blade

## 📝 Notes

- Les images sont des placeholders depuis `placeholder.com`
- Stripe n'est pas configuré par défaut (mode test)
- La base de données est SQLite pour faciliter le développement

## 👨‍💻 Développé avec Laravel 11

<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
