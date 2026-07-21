# Laundry Manager

Application Laravel 12 pour la gestion d'une blanchisserie, avec administration, connexion des blanchisseries, prise de commandes, suivi par QR/track, génération de PDF et envoi WhatsApp.

## Description

Ce projet est une plateforme de gestion pour une laverie :

- administration centralisée des blanchisseries
- authentification des blanchisseries
- gestion des clients et des commandes
- mise à jour du statut de commande (`received`, `cleaning`, `ready`, `delivered`)
- génération d'une facture / bon de commande PDF
- suivi public des commandes via un `tracking_token`
- partage de la commande par WhatsApp

## Technologies

- PHP 8.2+ et Laravel 12
- Blade / Laravel MVC
- Vite + Tailwind CSS
- Livewire
- Barryvdh DOMPDF pour PDF
- MySQL / SQLite / base de données relationnelle

## Fonctionnalités principales

### Authentification

- `admin` pour gérer les blanchisseries
- `laundry` pour les comptes de blanchisserie
- middleware de protection `auth.admin` et `auth.laundry`

### Gestion des blanchisseries

- pages d'administration pour créer, afficher, modifier et supprimer les blanchisseries
- chaque blanchisserie possède une adresse e-mail, un téléphone, un mot de passe et un statut actif

### Gestion des clients

- chaque blanchisserie peut créer des clients
- les clients sont liés à une blanchisserie
- recherche et listing côté blanchisserie

### Gestion des commandes

- création de commande avec client existant ou nouveau client
- types de services supportés : `غسيل`, `كي`, `غسيل+كي`
- suivi du nombre de pièces, couleur des pièces, prix, date de réception
- génération automatique de `order_number` et `tracking_token`
- la commande est liée à une blanchisserie et à un client

### Suivi public

- URL publique `suivi/{tracking_token}`
- permet à un client de consulter l’état de sa commande sans authentification

### PDF et WhatsApp

- génération de PDF via `OrderController::pdf()` et la vue `resources/views/pdf/order.blade.php`
- partage de l’état de commande et du lien de suivi via WhatsApp

## Structure du projet

- `app/Http/Controllers/Admin/` : contrôleurs d'administration
- `app/Http/Controllers/Laundry/` : contrôleurs pour les blanchisseries
- `app/Http/Controllers/TrackingController.php` : page de suivi public
- `app/Models/` : modèles Eloquent (`Laundry`, `Client`, `Order`, `Admin`, `User`)
- `database/migrations/` : schéma des tables
- `resources/views/` : vues Blade
- `routes/web.php` : routes principales

## Pages / Interfaces

- `admin/login` : page de connexion admin (`resources/views/admin/login.blade.php`)
- `admin/dashboard` : tableau de bord admin (`resources/views/admin/dashboard.blade.php`)
- `admin/laundries/create` : création de blanchisserie (`resources/views/admin/laundries/create.blade.php`)
- `admin/laundries/{id}/edit` : modification de blanchisserie (`resources/views/admin/laundries/edit.blade.php`)
- `laundry/login` : connexion blanchisserie (`resources/views/laundry/login.blade.php`)
- `laundry/dashboard` : tableau de bord blanchisserie (`resources/views/laundry/dashboard.blade.php`)
- `laundry/clients` : liste des clients (`resources/views/laundry/clients/index.blade.php`)
- `laundry/clients/create` : création de client (`resources/views/laundry/clients/create.blade.php`)
- `laundry/orders` : liste des commandes (`resources/views/laundry/orders/index.blade.php`)
- `laundry/orders/create` : création de commande (`resources/views/laundry/orders/create.blade.php`)
- `laundry/orders/{id}` : détail de commande (`resources/views/laundry/orders/show.blade.php`)
- `suivi/{tracking_token}` : suivi public de la commande (`resources/views/tracking/show.blade.php`)
- `pdf/order` : rendu PDF de la commande (`resources/views/pdf/order.blade.php`)

## Installation

### Prérequis

- PHP 8.2+
- Composer
- Node.js + npm
- Base de données MySQL ou SQLite

### Étapes

1. Cloner le projet

```bash
git clone <repository-url>
cd laundry-manager
```

2. Installer les dépendances PHP

```bash
composer install
```

3. Installer les dépendances frontend

```bash
npm install
```

4. Copier et configurer l’environnement

```bash
cp .env.example .env
php artisan key:generate
```

5. Configurer la base de données dans `.env`

- `DB_CONNECTION=mysql` ou `sqlite`
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

6. Lancer les migrations

```bash
php artisan migrate
```

7. Compiler les assets

```bash
npm run build
```

### Commandes utiles

- `php artisan serve` : démarre le serveur de développement
- `npm run dev` : lance Vite en mode développement
- `php artisan test` : exécute les tests Laravel

## Commandes Composer définies

- `composer setup` : installe les dépendances, crée `.env`, génère la clé d’application, exécute les migrations, installe npm et compile
- `composer dev` : lance le serveur, l’écoute de la queue, pail, et Vite en parallèle
- `composer test` : nettoie la config et exécute les tests

## Environnements spéciaux

- `admin` : gestion des blanchisseries
- `laundry` : gestion des clients, commandes et PDF
- `suivi/{tracking_token}` : suivi public

## Notes

- Si `npm` n’est pas reconnu, installez Node.js depuis https://nodejs.org et redémarrez PowerShell.
- Si vous utilisez SQLite, créez le fichier `database/database.sqlite` et mettez `DB_CONNECTION=sqlite` dans `.env`.

## Licence

Projet open-source sous licence MIT.
