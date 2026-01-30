#!/usr/bin/env bash
# Script de vérification - Boutique en Ligne Laravel

echo "🔍 Vérification de la Boutique en Ligne Laravel"
echo "=================================================="

# Vérifier PHP
echo ""
echo "✓ PHP Version:"
php -v | head -n 1

# Vérifier Composer
echo ""
echo "✓ Composer Version:"
composer --version

# Vérifier Node.js
echo ""
echo "✓ Node.js Version:"
node --version

# Vérifier npm
echo ""
echo "✓ npm Version:"
npm --version

# Lister les fichiers clés
echo ""
echo "✓ Fichiers du Projet:"
echo "  - app/Models/ ($(ls -1 app/Models/ | wc -l) fichiers)"
echo "  - app/Http/Controllers/ ($(ls -1 app/Http/Controllers/ | grep -v 'Auth\|Profile' | wc -l) contrôleurs)"
echo "  - resources/views/ ($(find resources/views/ -name '*.blade.php' | wc -l) vues)"
echo "  - database/migrations/ ($(ls -1 database/migrations/ | wc -l) migrations)"

# Vérifier les migrations
echo ""
echo "✓ État des Migrations:"
php artisan migrate:status | tail -n +3

# Vérifier la base de données
echo ""
echo "✓ Tables de la Base de Données:"
sqlite3 database/database.sqlite ".tables"

# Vérifier les routes
echo ""
echo "✓ Routes Principales:"
php artisan route:list | grep -E 'products|cart|orders' | wc -l

# Vérifier les fichiers de documentation
echo ""
echo "✓ Documentation:"
for file in README.md QUICKSTART.md API_ENDPOINTS.md STRIPE_INTEGRATION.md ARCHITECTURE.md PROJECT_SUMMARY.md ADVANCED_USE_CASES.md
do
    if [ -f "$file" ]; then
        lines=$(wc -l < "$file")
        echo "  ✓ $file ($lines lignes)"
    fi
done

# Vérifier l'environnement
echo ""
echo "✓ Configuration Environnement:"
grep "APP_KEY\|DB_CONNECTION\|SESSION_DRIVER" .env

# Résumé
echo ""
echo "✅ Vérification Complète!"
echo ""
echo "Pour démarrer:"
echo "  php artisan serve"
echo ""
echo "Pour consulter la documentation:"
echo "  - README.md - Documentation complète"
echo "  - QUICKSTART.md - Démarrage rapide"
echo "  - API_ENDPOINTS.md - Tous les endpoints"
