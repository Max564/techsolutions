# 📊 DOCUMENTATION BASE DE DONNÉES - CONFIGURATIONS PC TECHSOLUTIONS

## Vue d'ensemble

Cette base de données gère les configurations matérielles et logicielles par métier pour l'entreprise TechSolutions.

### Statistiques
- **7 métiers** différents
- **140+ composants** PC catalogués
- **30+ périphériques** (dont équipements d'accessibilité)
- **28 logiciels** référencés
- **Configuration complète** pour 50 postes

---

## 📁 STRUCTURE DE LA BASE

### Tables Principales

#### 1. `metiers`
Départements de l'entreprise
- 7 métiers (Développement, Infrastructure, Design, Marketing, Support, RH, Direction)
- Total: 50 postes

#### 2. `composants_pc`
Composants matériels PC
- 8 catégories (Processeur, Carte Mère, RAM, Stockage, GPU, Alimentation, Boîtier, Refroidissement)
- ~40 composants catalogués
- Prix unitaires et justifications

#### 3. `peripheriques`
Périphériques externes
- 5 catégories (Affichage, Saisie, Audio/Vidéo, Impression, Accessibilité)
- ~30 périphériques
- **Équipements d'accessibilité** pour handicap visuel

#### 4. `logiciels`
Logiciels et licences
- OS, Suite bureautique, Antivirus, Développement, Design, Infrastructure, Accessibilité
- 28 logiciels référencés
- Licences perpétuelles et abonnements

#### 5. `configurations_pc`
Association métier ↔ composants PC
- Définit quelle configuration pour quel métier
- Priorité: essentiel / recommandé / optionnel

#### 6. `configurations_peripheriques`
Association métier ↔ périphériques
- Définit les périphériques par métier
- Inclut équipements d'accessibilité

#### 7. `configurations_logiciels`
Association métier ↔ logiciels
- Définit les logiciels par métier
- Priorités et notes

---

## 🎯 CONFIGURATIONS PAR MÉTIER

### 1. DÉVELOPPEMENT LOGICIEL (15 postes)

**Matériel:**
- **CPU:** AMD Ryzen 9 7950X3D (seniors) / Intel i7-14700K (juniors)
- **RAM:** 64GB DDR5
- **Stockage:** 2TB NVMe PCIe 4.0
- **GPU:** RTX 4060 8GB (support CUDA)
- **Écrans:** 2x Dell U2723DE 27" QHD

**Justification:** Puissance pour compilation, VMs, conteneurs Docker

**Logiciels:**
- Windows 11 Pro + Ubuntu (dual boot)
- Visual Studio Professional 2022
- IntelliJ IDEA Ultimate
- Docker Desktop
- Git/GitHub

**Coût estimé par poste:** ~4500€

---

### 2. GESTION INFRASTRUCTURES SYSTÈMES ET RÉSEAU (5 postes)

**Matériel:**
- **CPU:** AMD Ryzen 9 7950X3D
- **RAM:** 64GB DDR5
- **Stockage:** 2TB SSD + 4TB HDD
- **Écrans:** 2x 27" pour surveillance

**Justification:** Virtualisation multiple, administration serveurs

**Logiciels:**
- Windows 11 Pro Workstations
- Ubuntu Desktop
- VMware Workstation Pro
- Veeam Backup & Replication
- Wireshark

**Coût estimé par poste:** ~4200€

---

### 3. DESIGN UX/UI (5 postes)

**Matériel:**
- **CPU:** AMD Ryzen 7 7800X3D
- **RAM:** 32GB DDR5
- **GPU:** RTX 4070 12GB (accélération Adobe)
- **Écran:** BenQ SW271C 27" 4K calibré Adobe RGB

**Justification:** Performance créative, rendu GPU, calibrage couleur

**Logiciels:**
- Adobe Creative Cloud All Apps
- Figma Professional
- Affinity Designer

**Coût estimé par poste:** ~4800€

---

### 4. MARKETING ET VENTE (10 postes)

**Matériel:**
- **CPU:** Intel i5-14600K
- **RAM:** 32GB DDR4
- **GPU:** Intel UHD 770 (intégré)
- **Écran:** LG 27UK850 27" 4K

**Justification:** Bureautique renforcée, présentations, multimédia

**Logiciels:**
- Microsoft 365 Business Standard
- Adobe CC Express

**Coût estimé par poste:** ~2200€

---

### 5. SUPPORT CLIENT (5 postes dont 1 ADAPTÉ)

#### 5.1 Configuration Standard (4 postes)

**Matériel:**
- **CPU:** AMD Ryzen 5 7600X
- **RAM:** 32GB DDR4
- **Écrans:** 2x ASUS 24" (tickets + doc)
- **Casque:** Logitech H390 USB

**Justification:** Multi-sessions support, tickets, documentation

**Logiciels:**
- Windows 11 Pro
- Microsoft 365 Apps

**Coût estimé:** ~1800€/poste

#### 5.2 🦮 **POSTE ADAPTÉ HANDICAP VISUEL** (1 poste)

**Matériel Spécialisé:**
- **Écran:** Samsung ViewFinity S8 32" 4K avec fonctions accessibilité (699€)
- **Clavier:** MaxiAids grands caractères contrastés + rétroéclairage (89€)
- **Souris:** Kensington Expert Mouse Trackball ergonomique (119€)
- **Casque:** Sennheiser RS 175 RF amplifié (279€)

**Logiciels d'Accessibilité:**
- **JAWS Professional** - Lecteur d'écran leader (1095€)
- **ZoomText Magnifier/Reader** - Grossissement jusqu'à 60x (599€)
- **NVDA** - Lecteur écran open source backup (Gratuit)
- **Windows Magnifier** - Loupe intégrée (Gratuit)

**Justifications:**
- **Écran 32" 4K:** Permet grossissement important sans perte qualité
- **Clavier grands caractères:** Caractères 3x plus grands, contraste élevé
- **Trackball:** Stationnaire, réduit mouvements, précis avec logiciels grossissement
- **JAWS:** Standard professionnel lecteur d'écran, synthèse vocale haute qualité
- **ZoomText:** Grossissement avancé avec suivi focus et curseur

**Coût total poste adapté:** ~4500€ (incluant logiciels spécialisés)

---

### 6. RESSOURCES HUMAINES (5 postes)

**Matériel:**
- **CPU:** AMD Ryzen 5 7600X
- **RAM:** 16GB DDR4
- **Stockage:** 500GB SSD
- **Écran:** ASUS 24" Full HD

**Justification:** Bureautique standard suffisante

**Logiciels:**
- Microsoft 365 Business Standard

**Coût estimé par poste:** ~1200€

---

### 7. DIRECTION (5 postes)

**Matériel:**
- **CPU:** Intel i7-14700K
- **RAM:** 32GB DDR5
- **GPU:** AMD RX 7700 XT (présentations)
- **Écran:** BenQ 27" 4K calibré
- **Watercooling:** Corsair Elite LCD (esthétique)

**Justification:** Configuration premium, image, présentations haute qualité

**Logiciels:**
- Microsoft 365 Business Standard
- Adobe CC Express

**Coût estimé par poste:** ~4000€

---

## 💰 BUDGET GLOBAL ENTREPRISE

### Récapitulatif par Département

| Département | Postes | Coût/Poste | Coût Total |
|-------------|--------|------------|------------|
| Développement | 15 | 4500€ | 67 500€ |
| Infrastructure | 5 | 4200€ | 21 000€ |
| Design UX/UI | 5 | 4800€ | 24 000€ |
| Marketing | 10 | 2200€ | 22 000€ |
| Support (standard) | 4 | 1800€ | 7 200€ |
| Support (adapté) | 1 | 4500€ | 4 500€ |
| RH/Admin | 5 | 1200€ | 6 000€ |
| Direction | 5 | 4000€ | 20 000€ |
| **TOTAL** | **50** | **~3440€** | **172 200€** |

### Répartition des Coûts

- **Composants PC:** ~60% (103 000€)
- **Périphériques:** ~25% (43 000€)
- **Logiciels:** ~15% (26 000€)

---

## 🦮 ÉQUIPEMENTS D'ACCESSIBILITÉ DÉTAILLÉS

### Matériel Spécialisé

1. **Samsung ViewFinity S8 32"** (699€)
   - Grande taille pour grossissement
   - 4K pour netteté maximale
   - Contraste élevé
   - Fonctions accessibilité intégrées

2. **MaxiAids Clavier grands caractères** (89€)
   - Caractères 3x plus grands
   - Contraste jaune/noir ou blanc/noir
   - Rétroéclairage LED réglable
   - Touches bien espacées

3. **Kensington Expert Mouse Trackball** (119€)
   - Stationnaire (pas de mouvement bras)
   - Précision pour grossissement
   - 4 boutons programmables larges
   - Repose-poignets confort

4. **Sennheiser RS 175 RF** (279€)
   - Amplification audio réglable
   - Transmission RF sans compression
   - Modes auditifs personnalisables
   - Volume indépendant

### Logiciels Spécialisés

1. **JAWS Professional** (1095€) - ESSENTIEL
   - Lecteur d'écran leader mondial
   - Synthèse vocale haute qualité
   - Support braille complet
   - Macros personnalisables
   - Standard professionnel

2. **ZoomText Magnifier/Reader** (599€) - RECOMMANDÉ
   - Grossissement jusqu'à 60x
   - Suivi automatique focus/curseur
   - Palettes de couleurs contrastées
   - Lecture vocale intégrée

3. **NVDA** (Gratuit) - BACKUP
   - Lecteur d'écran open source
   - Alternative viable à JAWS
   - Communauté active
   - Mises à jour fréquentes

4. **Windows Magnifier** (Gratuit) - BASIQUE
   - Loupe native Windows
   - Modes: plein écran, lentille, ancré
   - Gratuit et toujours disponible
   - Suffisant pour déficience légère

**Coût total équipements accessibilité:** 2880€

---

## 📊 VUES SQL UTILES

### 1. Configuration Complète par Métier
```sql
SELECT * FROM v_configurations_completes 
WHERE nom_metier = 'Support client';
```

### 2. Coût Total par Métier
```sql
SELECT * FROM v_cout_par_metier 
ORDER BY cout_total_departement DESC;
```

### 3. Équipements d'Accessibilité
```sql
SELECT * FROM v_equipements_accessibilite;
```

### 4. Budget Global Entreprise
```sql
SELECT 
    SUM(cout_total_departement) as budget_total,
    SUM(nombre_postes) as total_postes,
    AVG(cout_total_par_poste) as cout_moyen
FROM v_cout_par_metier;
```

---

## 🔧 UTILISATION DE LA BASE

### Installation
```bash
mysql -u root -p < schema_configurations.sql
```

### Connexion
```bash
mysql -u root -p techsolutions_config
```

### Requêtes Courantes

**Lister tous les métiers:**
```sql
SELECT * FROM metiers;
```

**Configuration d'un métier spécifique:**
```sql
SELECT * FROM v_configurations_completes 
WHERE nom_metier = 'Développement logiciel';
```

**Tous les périphériques d'accessibilité:**
```sql
SELECT * FROM peripheriques WHERE accessibilite = TRUE;
```

**Logiciels par métier:**
```sql
SELECT m.nom_metier, l.nom_logiciel, l.editeur, l.prix_unitaire
FROM configurations_logiciels cl
JOIN logiciels l ON cl.id_logiciel = l.id_logiciel
JOIN metiers m ON cl.id_metier = m.id_metier
ORDER BY m.nom_metier;
```

---

## ✅ CONFORMITÉ CAHIER DES CHARGES

### Infrastructure Informatique
- ✅ Composants PC choisis individuellement
- ✅ Aucun PC pré-assemblé
- ✅ PC fixes uniquement
- ✅ Tous périphériques nécessaires
- ✅ **Équipements accessibilité pour handicap visuel**
- ✅ Système d'exploitation compatible
- ✅ Suite bureautique adaptée
- ✅ Antivirus professionnel
- ✅ Solutions réseau et cybersécurité

### Accessibilité
- ✅ **Poste complet adapté handicap visuel**
- ✅ Matériel spécialisé (écran 32", clavier, trackball)
- ✅ Logiciels lecteurs d'écran (JAWS, NVDA)
- ✅ Logiciels de grossissement (ZoomText)
- ✅ Équipement audio amplifié
- ✅ **Budget dédié: 4500€**

### Direction
- ✅ Ordinateurs portables en plus des fixes (note: à ajouter)

---

## 📝 NOTES IMPORTANTES

1. **Tous les prix** sont estimés et peuvent varier selon les fournisseurs
2. **Licences logiciels** : privilégier licences volume pour économies
3. **Garanties** : prévoir extension 3 ans sur postes critiques
4. **Évolutivité** : configurations permettent upgrades futurs
5. **Accessibilité** : Budget spécifique dédié = engagement social

---

**Base de données créée:** 10 Décembre 2025  
**Version:** 1.0  
**Auteur:** TechSolutions BTS SIO  
**Statut:** ✅ Prêt pour production
