# Plan de Correction — Boucle de redirection `/admin/login`

## Objectif
Résoudre la boucle infinie de redirection sur la route `/admin/login` (et `/laundry/login`).

## Étapes

### Étape 1: Admin/AuthController.php
- [x] Supprimer le check de redirection auto dans `showLogin()` (lignes 14-17)
- [x] Remplacer `session()->invalidate()` par `session()->regenerate()` dans `logout()`

### Étape 2: Laundry/AuthController.php
- [x] Supprimer le check de redirection auto dans `showLogin()` (lignes 14-17)
- [x] Remplacer `session()->invalidate()` par `session()->regenerate()` dans `logout()`

### Étape 3: Vérification finale ✅
- [x] Tester que les pages de login s'affichent toujours → **HTTP 200** ✅
- [x] Tester la connexion admin → dashboard → **redirigé correctement vers dashboard** ✅
- [x] Tester la connexion laundry → dashboard → **redirigé correctement vers dashboard** ✅
- [x] Tester le logout des deux guards → **redirigé correctement vers login** ✅
- [x] Test des routes protégées sans auth → **redirect 302 vers login** ✅

