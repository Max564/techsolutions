# 📊 BASE DE DONNÉES TECHSOLUTIONS - UNIFIÉE ET COMPLÈTE

## 🎯 Vue d'ensemble

**UNE SEULE base de données** qui gère TOUT le projet TechSolutions:
- Site web (admin, clients, actualités, messages)
- Configurations PC par métier
- Catalogue complet matériel et logiciels
- Équipements d'accessibilité

**Nom de la base:** `techsolutions`

---

## 📁 STRUCTURE COMPLÈTE

### Partie 1: Site Web (7 tables)

1. **administrateurs** - Comptes admin du site
2. **clients** - Comptes clients avec RGPD
3. **actualites** - News et articles du site
4. **messages_contact** - Messages formulaire contact
5. **demandes_devis** - Demandes de devis clients
6. **parametres_site** - Configuration du site
7. **logs_activite** - Traçabilité des actions

### Partie 2: Configurations PC (10 tables)

8. **metiers** - 7 départements de l'entreprise
9. **categories_composants** - 8 catégories matériel PC
10. **composants_pc** - ~40 composants catalogués
11. **categories_peripheriques** - 5 catégories périphériques
12. **peripheriques** - ~30 périphériques (+ accessibilité)
13. **logiciels** - 30 logiciels professionnels
14. **configurations_pc** - Association métier → composants
15. **configurations_peripheriques** - Association métier → périphériques
16. **configurations_logiciels** - Association métier → logiciels

### Vues SQL (3 vues)

- **v_config_complete** - Configuration complète par métier
- **v_cout_metier** - Calcul coûts par département
- **v_accessibilite** - Liste équipements accessibilité

**TOTAL: 17 tables + 3 vues = 20 objets**

---

## 🚀 INSTALLATION ULTRA-SIMPLE

### Méthode unique - Un seul fichier!

```bash
# 1. Ouvrir MySQL
mysql -u root -p

# 2. Importer le fichier unique
source /chemin/vers/schema_complet_unifie.sql

# C'EST TOUT! ✅
```

Ou avec phpMyAdmin:
1. Ouvrir phpMyAdmin
2. Onglet "Importer"
3. Choisir `schema_complet_unifie.sql`
4. Exécuter

**La base `techsolutions` est créée automatiquement!**

---

## 📊 DONNÉES INCLUSES

### Site Web

✅ **1 administrateur** par défaut
- Username: `admin`
- Password: `admin123` (à changer!)
- Email: admin@techsolutions.fr

✅ **3 actualités** pré-remplies
- Partenariat stratégique
- Innovation cybersécurité
- Prix de l'innovation 2025

✅ **7 paramètres** du site
- Nom, adresse, téléphone, email, horaires

### Configurations PC

✅ **7 métiers** configurés
1. Développement logiciel (15 postes)
2. Infrastructure systèmes (5 postes)
3. Design UX/UI (5 postes)
4. Marketing et vente (10 postes)
5. **Support client (5 postes dont 1 ADAPTÉ)** ⭐
6. RH et administration (5 postes)
7. Direction (5 postes)

✅ **40+ composants PC**
- Processeurs (AMD, Intel)
- Cartes mères
- RAM DDR4/DDR5
- SSD/HDD
- Cartes graphiques
- Alimentations
- Boîtiers
- Refroidissement

✅ **30+ périphériques**
- Écrans professionnels
- Claviers (standard + accessibilité)
- Souris (standard + accessibilité)
- Webcams
- Casques
- Imprimantes

✅ **30 logiciels**
- OS (Windows, Ubuntu)
- Bureautique (Microsoft 365, LibreOffice)
- Sécurité (Bitdefender, Kaspersky)
- Développement (Visual Studio, Docker)
- Design (Adobe CC, Figma)
- Infrastructure (VMware, Veeam)
- **Accessibilité (JAWS, ZoomText, NVDA)** ⭐

---

## 🦮 POSTE ADAPTÉ HANDICAP VISUEL

### Configuration complète dans la base

**Matériel spécialisé (4 équipements):**

1. **Samsung ViewFinity S8 32"** - 699€
   ```sql
   SELECT * FROM peripheriques WHERE nom_peripherique LIKE '%ViewFinity%';
   ```

2. **MaxiAids Clavier grands caractères** - 89€
   ```sql
   SELECT * FROM peripheriques WHERE nom_peripherique LIKE '%MaxiAids%';
   ```

3. **Kensington Expert Trackball** - 119€
   ```sql
   SELECT * FROM peripheriques WHERE nom_peripherique LIKE '%Trackball%';
   ```

4. **Sennheiser RS 175 RF** - 279€
   ```sql
   SELECT * FROM peripheriques WHERE nom_peripherique LIKE '%Sennheiser%';
   ```

**Logiciels d'accessibilité (4 logiciels):**

1. **JAWS Professional** - 1095€
   ```sql
   SELECT * FROM logiciels WHERE nom_logiciel = 'JAWS Professional';
   ```

2. **ZoomText** - 599€
3. **NVDA** - Gratuit
4. **Windows Magnifier** - Gratuit

**Total poste adapté: 4500€**

### Voir le poste adapté complet

```sql
-- Tout le matériel et logiciels du poste adapté
SELECT * FROM v_config_complete 
WHERE nom_metier = 'Support client' 
  AND (notes LIKE '%ADAPTÉ%' OR notes LIKE '%POSTE ADAPTÉ%');

-- Uniquement les équipements d'accessibilité
SELECT * FROM v_accessibilite;
```

---

## 💡 REQUÊTES UTILES

### Site Web

```sql
-- Connexion admin
SELECT * FROM administrateurs WHERE username = 'admin';

-- Dernières actualités
SELECT * FROM actualites 
WHERE publie = TRUE 
ORDER BY date_publication DESC 
LIMIT 5;

-- Messages non lus
SELECT * FROM messages_contact 
WHERE lu = FALSE 
ORDER BY date_envoi DESC;

-- Paramètres du site
SELECT * FROM parametres_site;
```

### Configurations PC

```sql
-- Tous les métiers
SELECT * FROM metiers ORDER BY nombre_postes DESC;

-- Configuration complète d'un métier
SELECT * FROM v_config_complete 
WHERE nom_metier = 'Développement logiciel';

-- Budget par métier
SELECT 
    nom_metier,
    nombre_postes,
    cout_total_poste as 'Coût/poste',
    cout_total_departement as 'Coût département'
FROM v_cout_metier
ORDER BY cout_total_departement DESC;

-- Budget total entreprise
SELECT 
    SUM(cout_total_departement) as budget_total,
    SUM(nombre_postes) as total_postes,
    AVG(cout_total_poste) as cout_moyen_poste
FROM v_cout_metier;
```

### Accessibilité

```sql
-- Tous les équipements d'accessibilité
SELECT * FROM peripheriques WHERE accessibilite = TRUE;

-- Tous les logiciels d'accessibilité
SELECT * FROM logiciels WHERE type_logiciel = 'Accessibilité';

-- Configuration complète poste adapté
SELECT * FROM v_accessibilite ORDER BY prix_unitaire DESC;

-- Coût total accessibilité
SELECT SUM(prix_unitaire) as cout_total_accessibilite
FROM (
    SELECT prix_unitaire FROM peripheriques WHERE accessibilite = TRUE
    UNION ALL
    SELECT prix_unitaire FROM logiciels WHERE type_logiciel = 'Accessibilité'
) as equipements_accessibilite;
```

### Statistiques

```sql
-- Nombre de composants par catégorie
SELECT cc.nom_categorie, COUNT(*) as nombre
FROM composants_pc c
JOIN categories_composants cc ON c.id_categorie = cc.id
GROUP BY cc.nom_categorie;

-- Nombre de périphériques par catégorie
SELECT cp.nom_categorie, COUNT(*) as nombre
FROM peripheriques p
JOIN categories_peripheriques cp ON p.id_categorie = cp.id
GROUP BY cp.nom_categorie;

-- Logiciels par type
SELECT type_logiciel, COUNT(*) as nombre
FROM logiciels
GROUP BY type_logiciel
ORDER BY nombre DESC;

-- Répartition du budget
SELECT 
    'Composants PC' as categorie,
    SUM(prix_unitaire) as montant_total
FROM composants_pc
UNION ALL
SELECT 
    'Périphériques',
    SUM(prix_unitaire)
FROM peripheriques
UNION ALL
SELECT 
    'Logiciels',
    SUM(prix_unitaire)
FROM logiciels;
```

---

## 📈 EXEMPLES D'UTILISATION

### 1. Créer un nouveau client

```sql
INSERT INTO clients (email, password, nom, prenom, telephone)
VALUES (
    'jean.dupont@exemple.fr',
    '$2y$10$...', -- Hash du mot de passe
    'Dupont',
    'Jean',
    '0601020304'
);
```

### 2. Ajouter une actualité

```sql
INSERT INTO actualites (titre, contenu, auteur, publie)
VALUES (
    'Nouvelle offre Cloud',
    'TechSolutions lance son offre cloud sécurisée...',
    'Direction',
    TRUE
);
```

### 3. Enregistrer un message de contact

```sql
INSERT INTO messages_contact (nom, email, sujet, message)
VALUES (
    'Marie Martin',
    'marie@exemple.fr',
    'Demande d''information',
    'Bonjour, je souhaiterais...'
);
```

### 4. Ajouter un composant PC

```sql
INSERT INTO composants_pc (
    id_categorie, 
    type_composant, 
    nom_composant, 
    marque, 
    prix_unitaire, 
    justification
) VALUES (
    1, -- Processeur
    'Processeur',
    'AMD Ryzen 7 9800X3D',
    'AMD',
    499.99,
    'Nouveau processeur haute performance pour gaming et création'
);
```

### 5. Configurer un métier

```sql
-- Ajouter un composant à la configuration Développement
INSERT INTO configurations_pc (id_metier, id_composant, quantite, priorite)
VALUES (
    1, -- Développement logiciel
    1, -- AMD Ryzen 9
    1,
    'essentiel'
);
```

---

## 🔒 SÉCURITÉ

### Bonnes pratiques

1. **Changer le mot de passe admin** immédiatement
   ```sql
   UPDATE administrateurs 
   SET password = '$2y$10$nouveau_hash' 
   WHERE username = 'admin';
   ```

2. **Créer un utilisateur MySQL dédié**
   ```sql
   CREATE USER 'techsolutions'@'localhost' 
   IDENTIFIED BY 'mot_de_passe_fort';
   
   GRANT ALL PRIVILEGES ON techsolutions.* 
   TO 'techsolutions'@'localhost';
   ```

3. **Sauvegardes régulières**
   ```bash
   mysqldump -u root -p techsolutions > backup_$(date +%Y%m%d).sql
   ```

---

## 📊 STATISTIQUES DE LA BASE

```sql
-- Nombre total d'objets
SELECT 
    'Tables' as type, COUNT(*) as nombre
FROM information_schema.tables 
WHERE table_schema = 'techsolutions' 
  AND table_type = 'BASE TABLE'
UNION ALL
SELECT 
    'Vues', COUNT(*)
FROM information_schema.views
WHERE table_schema = 'techsolutions';

-- Taille de la base de données
SELECT 
    table_schema as 'Base de données',
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as 'Taille (MB)'
FROM information_schema.tables 
WHERE table_schema = 'techsolutions'
GROUP BY table_schema;
```

---

## 🎯 AVANTAGES DE LA BASE UNIFIÉE

### ✅ Simplicité
- **1 seul fichier** SQL à importer
- **1 seule base** de données à gérer
- **1 seule connexion** dans le code PHP

### ✅ Cohérence
- Toutes les données liées
- Intégrité référentielle garantie
- Pas de synchronisation entre bases

### ✅ Performance
- Requêtes JOIN possibles entre toutes les tables
- Index optimisés
- Vues précalculées

### ✅ Maintenance
- Backup unique
- Restauration simplifiée
- Migration facilitée

---

## 📞 CONNEXION DANS LE CODE PHP

```php
<?php
// includes/db.php
$host = 'localhost';
$dbname = 'techsolutions';  // UNE SEULE BASE!
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4", 
        $username, 
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur: " . $e->getMessage());
}
?>
```

---

## 🏆 RÉSUMÉ FINAL

### Ce que contient cette base unique:

✅ **Site web complet**
- Administration
- Espace client RGPD
- Actualités
- Messages contact

✅ **Configurations PC**
- 7 métiers / 50 postes
- 40+ composants PC
- 30+ périphériques
- 30 logiciels

✅ **Accessibilité**
- Poste complet adapté handicap visuel
- Équipements spécialisés
- Logiciels dédiés (JAWS, ZoomText)
- Budget: 4500€

✅ **Vues SQL pratiques**
- Configuration par métier
- Coûts calculés automatiquement
- Équipements accessibilité

### Budget total: 172 200€ pour 50 postes

---

**Base de données créée:** 10 Décembre 2025  
**Fichier:** `schema_complet_unifie.sql`  
**Statut:** ✅ PRÊT POUR PRODUCTION  

**Une seule base pour tout gérer! 🎯**
