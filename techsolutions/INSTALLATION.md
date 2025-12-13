# 🚀 Installation Rapide - TechSolutions

## Méthode 1: Installation avec XAMPP/WAMP (Recommandé pour débutants)

### Étape 1: Copier les fichiers
1. Copiez le dossier `techsolutions` dans:
   - **XAMPP**: `C:\xampp\htdocs\`
   - **WAMP**: `C:\wamp64\www\`
   - **MAMP**: `/Applications/MAMP/htdocs/`

### Étape 2: Créer la base de données
1. Ouvrez phpMyAdmin: `http://localhost/phpmyadmin`
2. Cliquez sur "Nouvelle base de données"
3. Nom: `techsolutions`
4. Interclassement: `utf8mb4_unicode_ci`
5. Cliquez sur "Créer"

### Étape 3: Importer la structure
1. Sélectionnez la base `techsolutions`
2. Cliquez sur l'onglet "Importer"
3. Choisissez le fichier `database/schema.sql`
4. Cliquez sur "Exécuter"

### Étape 4: Vérifier la configuration
1. Ouvrez le fichier `includes/db.php`
2. Vérifiez que les paramètres sont:
   ```php
   $host = 'localhost';
   $dbname = 'techsolutions';
   $username = 'root';
   $password = '';  // Vide pour XAMPP/WAMP par défaut
   ```

### Étape 5: Accéder au site
- **Site:** http://localhost/techsolutions/
- **Admin:** http://localhost/techsolutions/admin/login.php
  - User: `admin`
  - Pass: `admin123`

---

## Méthode 2: Installation sur serveur Linux

### Commandes rapides:
```bash
# 1. Copier les fichiers
sudo cp -r techsolutions /var/www/html/

# 2. Créer la base de données
sudo mysql -u root -p

# Dans MySQL:
CREATE DATABASE techsolutions CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;

# 3. Importer la structure
sudo mysql -u root -p techsolutions < /var/www/html/techsolutions/database/schema.sql

# 4. Définir les permissions
sudo chown -R www-data:www-data /var/www/html/techsolutions
sudo chmod -R 755 /var/www/html/techsolutions

# 5. Redémarrer Apache
sudo systemctl restart apache2
```

### Accès:
- **Site:** http://votre-ip/techsolutions/
- **Admin:** http://votre-ip/techsolutions/admin/login.php

---

## ⚠️ Problèmes Courants

### Erreur: "Connexion à la base de données échouée"
**Solution:** Vérifiez `includes/db.php` et modifiez les identifiants

### Erreur: "Page blanche"
**Solution:** 
1. Vérifiez les logs PHP
2. Activez l'affichage des erreurs dans `php.ini`:
   ```ini
   display_errors = On
   error_reporting = E_ALL
   ```

### Erreur: "Permission denied"
**Solution:** 
```bash
sudo chmod -R 755 /var/www/html/techsolutions
```

---

## 📧 Premier Test

1. Accédez au site
2. Remplissez le formulaire de contact
3. Connectez-vous en admin
4. Vérifiez que le message apparaît

**Identifiants admin par défaut:**
- Utilisateur: `admin`
- Mot de passe: `admin123`

⚠️ **N'oubliez pas de changer ce mot de passe!**

---

## 🎯 Prochaines Étapes

1. ✅ Tester toutes les fonctionnalités
2. ✅ Modifier le mot de passe admin
3. ✅ Personnaliser les couleurs (css/styles.css)
4. ✅ Ajouter vos actualités
5. ✅ Configurer l'envoi d'emails (includes/contact.php)
6. ✅ Consulter les fichiers Excel générés

---

**Besoin d'aide ?** Consultez le fichier README.md complet
