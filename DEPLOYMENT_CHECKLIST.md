# 🚀 Checklist Déploiement - Boutique en Ligne

## ✅ Avant le Déploiement (Environnement de Développement)

### Tests Locaux
- [ ] Lancer `php artisan serve`
- [ ] Tester la page d'accueil
- [ ] Tester le catalogue (accès, recherche, filtrage)
- [ ] Tester le panier (ajouter, modifier, supprimer)
- [ ] Tester l'authentification (inscription, connexion, déconnexion)
- [ ] Tester les commandes (créer une commande, historique)
- [ ] Tester le paiement en mode test

### Vérifications du Code
- [ ] Vérifier les erreurs PHP: `php -l app/**/*.php`
- [ ] Exécuter les tests: `php artisan test`
- [ ] Vérifier la linting: `composer pint`
- [ ] Vérifier les dépendances: `composer audit`

### Base de Données
- [ ] Tester avec données de test: `php artisan migrate:fresh --seed`
- [ ] Vérifier les migrations: `php artisan migrate:status`
- [ ] Tester la performance avec +100 produits

### Sécurité
- [ ] Vérifier CSRF sur tous les formulaires
- [ ] Tester l'autorisation d'accès
- [ ] Vérifier que les secrets ne sont pas exposés
- [ ] Tester la validation côté serveur

---

## 🔧 Préparation du Serveur

### Serveur Web (Apache/Nginx)
- [ ] Installer PHP 8.2+
- [ ] Installer les extensions PHP requises:
  - [ ] php-json
  - [ ] php-xml
  - [ ] php-mbstring
  - [ ] php-bcmath
  - [ ] php-sqlite3 (ou autre DB)
  
- [ ] Configurer le serveur:
  - [ ] Document root → `public/`
  - [ ] Permissions → `storage/` (775)
  - [ ] Permissions → `bootstrap/cache/` (775)

### Dépendances
- [ ] Installer Composer
- [ ] Installer Node.js & npm
- [ ] Cloner le projet
- [ ] Exécuter `composer install --no-dev`
- [ ] Exécuter `npm install`
- [ ] Exécuter `npm run build`

---

## ⚙️ Configuration Environnement

### Fichier .env
```bash
# Copier et modifier
cp .env.example .env

# À configurer:
APP_NAME=BoutiqueEnLigne
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

# Base de données
DB_CONNECTION=mysql
DB_HOST=votre-host
DB_DATABASE=votre_db
DB_USERNAME=votre_user
DB_PASSWORD=votre_password

# Clés de sécurité
STRIPE_PUBLIC_KEY=pk_live_...
STRIPE_SECRET_KEY=sk_live_...

# Email
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
```

### Générer les Clés
```bash
php artisan key:generate
php artisan storage:link
```

---

## 🗄️ Base de Données

### Migration en Production
```bash
# Backup de la BD existante (si applicable)
mysqldump -u user -p database > backup.sql

# Exécuter les migrations
php artisan migrate --force

# Charger les données initiales
php artisan db:seed --class=ProductSeeder
```

### Vérification
```bash
php artisan migrate:status
php artisan tinker
> Product::count()
```

---

## 🔒 Sécurité en Production

### Configuration HTTPS
- [ ] Installer certificat SSL (Let's Encrypt)
- [ ] Configurer HSTS (HTTP Strict Transport Security)
- [ ] Mettre à jour `APP_URL` en `https://`

### Variables d'Environnement
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] Les secrets doivent être en variables d'environnement
- [ ] Utiliser un gestionnaire de secrets (Vault, AWS Secrets, etc.)

### Permissions & Propriétaire
```bash
# Définir le propriétaire
chown -R www-data:www-data /path/to/app

# Permissions
chmod -R 755 /path/to/app
chmod -R 775 /path/to/app/storage
chmod -R 775 /path/to/app/bootstrap/cache
```

### Pare-feu & Fail2Ban
- [ ] Configurer les ports (80, 443)
- [ ] Bloquer les accès non autorisés
- [ ] Configurer rate limiting
- [ ] Installer Fail2Ban pour les attaques brutes

---

## 🚀 Déploiement

### Avec Git
```bash
# Sur le serveur
cd /path/to/app
git pull origin main

# Installer les dépendances
composer install --no-dev
npm install
npm run build

# Mettre à jour la BD
php artisan migrate --force

# Nettoyer les caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan cache:clear
```

### Avec FTP/SFTP
1. Uploader tous les fichiers (sauf `.env` local)
2. Configurer `.env` sur le serveur
3. Exécuter les migrations
4. Vérifier les permissions

### Avec Docker
```dockerfile
FROM php:8.2-fpm

# Installer les extensions
RUN docker-php-ext-install pdo pdo_mysql bcmath json

# Copier le code
COPY . /app
WORKDIR /app

# Installer les dépendances
RUN composer install --no-dev
RUN npm install && npm run build

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
```

---

## ✅ Vérifications Post-Déploiement

### Tests Fonctionnels
- [ ] Accéder au site en HTTPS
- [ ] Tester le catalogue
- [ ] Tester le panier
- [ ] Tester l'authentification
- [ ] Tester les commandes
- [ ] Tester le paiement Stripe

### Monitoring
- [ ] Vérifier les logs: `tail -f storage/logs/laravel.log`
- [ ] Vérifier les erreurs 5xx
- [ ] Vérifier les emails de notification

### Performance
- [ ] Tester la vitesse avec GTmetrix ou PageSpeed Insights
- [ ] Vérifier les requêtes SQL (SELECT N+1)
- [ ] Vérifier la cache hit rate
- [ ] Monitorer la charge serveur

---

## 🔄 Maintenance Post-Déploiement

### Backups Automatisés
```bash
# Backup quotidien
0 2 * * * mysqldump -u user -p database | gzip > /backups/$(date +\%Y-\%m-\%d).sql.gz

# Backup des fichiers
0 3 * * * tar -czf /backups/app-$(date +\%Y-\%m-\%d).tar.gz /path/to/app
```

### Mise à Jour des Dépendances
```bash
# Vérifier les vulnérabilités
composer audit

# Mettre à jour
composer update
npm update
```

### Monitoring Continu
- [ ] Configurer les alertes (New Relic, DataDog)
- [ ] Monitorer les performances
- [ ] Vérifier les logs d'erreur
- [ ] Vérifier les transactions Stripe

---

## 🚨 Plan de Récupération

### En Cas d'Erreur
```bash
# Voir les derniers logs
tail -100f storage/logs/laravel.log

# Redémarrer les services
systemctl restart nginx
systemctl restart php8.2-fpm
systemctl restart mysql

# Restaurer depuis backup
mysql -u user -p database < backup.sql
```

### Rollback Rapide
```bash
# Si déploiement récent
git revert HEAD
git push origin main

# Réappliquer la migration précédente
php artisan migrate:rollback
```

---

## 📞 Support Production

### Erreurs Courantes

**502 Bad Gateway**
- Vérifier les permissions de `storage/`
- Vérifier que PHP-FPM est actif
- Vérifier les logs nginx

**500 Internal Server Error**
- Vérifier `.env` sur le serveur
- Vérifier les logs Laravel
- Vérifier la BD

**Session Expire Rapidement**
- Augmenter `SESSION_LIFETIME` dans `.env`
- Vérifier le driver de session
- Vérifier les permissions de `storage/framework/sessions/`

**Mails Non Reçus**
- Vérifier la configuration SMTP
- Vérifier les logs
- Vérifier le dossier spam

---

## 📊 Checklist Final

### Avant la Production
- [ ] Tests en staging
- [ ] Backup de la BD
- [ ] Plan de récupération
- [ ] Monitoring configuré
- [ ] Team prêt pour support
- [ ] Documentation mise à jour

### Après le Déploiement
- [ ] Tous les tests passent ✓
- [ ] Monitoring actif ✓
- [ ] Alertes configurées ✓
- [ ] Équipe notifiée ✓
- [ ] Documentation mise à jour ✓

---

## 🎯 Ressources Utiles

- [Laravel Deployment](https://laravel.com/docs/11.x/deployment)
- [Nginx Configuration](https://laravel.com/docs/11.x/deployment#nginx)
- [SSL Let's Encrypt](https://certbot.eff.org/)
- [AWS Deployment](https://aws.amazon.com/getting-started/hands-on/deploy-laravel/)
- [DigitalOcean Guide](https://www.digitalocean.com/community/tutorials)

---

**Dernier déploiement:** 30 Janvier 2026  
**État:** ✅ Prêt pour production
