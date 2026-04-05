# 🍔🍩 Flagrant Délice - Projet Creative Yumland (Pré-ing 2 CY Tech 2025-26)

> **"Mélanges Interdits, Saveurs Criminelles."**

Bienvenue sur le dépôt officiel du projet **Flagrant Délice**.

Le concept ? Un restaurant audacieux spécialisé dans les **"crimes culinaires"** : des mélanges interdits mais délicieux comme le Burger Donut, la Pizza Hawaïenne ou les Frites au Chocolat.

## Ce projet est réalisé par le trinôme suivant :
* **Ahmed Eish**
* **Naim Mohammed**
* **Tarek Itim**

---

## État d'Avancement du Projet

- **Phase 1 : Interface Statique (HTML5 / CSS3)** - *Terminée* ✅
- **Phase 2 : Dynamisation Serveur (PHP / MySQL)** - *Terminée* ✅
- **Phase 3 : Dynamisation Client (JavaScript / AJAX)** - *À venir* ⏳
- **Phase 4 : Bonnes Pratiques & Optimisation** - *À venir* ⏳

---

## 🎨 Charte Graphique

L'identité visuelle repose sur un contraste fort pour souligner le côté "décalé" du restaurant :
* **Bleu Cyan (`#00a8e8`)** : Couleur principale (Fonds, bordures, titres).
* **Rouge Vif (`#e60012`)** : Couleur d'accentuation (Boutons, actions importantes, alertes).
* **Typographie** : Utilisation des polices `Impact` et `Arial Black` pour les titres afin de donner un effet "Headline" ou "Breaking News".

---

## 📂 Contenu de la Phase 1

La phase 1 se concentre exclusivement sur l'intégration graphique et la structure des pages (Front-End statique). Aucune base de données n'est connectée pour le moment.

### Arborescence des fichiers

* **Partie Client :**
    * `accueil.html` : Page d'accueil (Vitrine des plats populaires et barre de recherche).
    * `presentation.html` : La carte complète avec filtres visuels (Catégories, Allergènes).
    * `inscription.html` : Formulaire de création de compte complet.
    * `connexion.html` : Page d'identification.
    * `profil.html` : Espace client (Informations, Fidélité, Historique des commandes).
    * `notation.html` : Formulaire d'avis après livraison.

* **Partie Staff (Restaurateur & Livreur) :**
    * `admin.html` : Dashboard pour l'administrateur (gestion clients et debug).
    * `commandes.html` : Interface tablette pour la cuisine (suivi des préparations).
    * `livraison.html` : Interface mobile pour le livreur (détails commande, GPS, appel).
        * *Note :* Design adapté pour une utilisation avec des "gros gants" (boutons larges).

* **Ressources :**
    * `style.css` : Feuille de style unique gérant la charte graphique.
    * `images/` : Dossier contenant les visuels des plats et le logo.

---

## 🛠️ Problèmes connus & Solutions (Phase 1)

* **Responsive Livreur :** L'interface `livraison.html` a été spécifiquement codée avec `box-sizing: border-box` et des largeurs en pourcentage pour s'adapter aux écrans mobiles et faciliter le clic (contrainte des gants).
* **Formulaires :** Les formulaires (recherche, filtres, inscription) sont visuels. Ils redirigent vers les pages cibles via `action="..."` mais ne traitent pas encore les données (cela sera fait en Phase 2 avec PHP).

---

## 📂 Contenu de la Phase 2

La Phase 2 marque le passage d'un site statique à une application web dynamique pilotée par le serveur via PHP et des fichiers de données structurés.

### Architecture Dynamique (PHP)
* **Pages Clients** :
    * `accueil.php` : Page d'accueil gérant l'état de connexion.
    * `presentation.php` : Affichage dynamique du catalogue de plats à partir de `plats.json` avec gestion du panier.
    * `panier.php` : Tunnel de commande complet incluant le choix du mode de consommation (sur place, emporter, livraison) et l'intégration de l'API CYBank.
    * `profil.php` : Espace personnel affichant les points de fidélité et l'historique des commandes filtré par client.
    * `notation.php` : Système de notation des livraisons et des repas.
* **Interfaces Staff & Admin** :
    * `admin.php` : Dashboard de gestion de tous les utilisateurs inscrits avec outils de debug.
    * `commandes.php` : Gestion des statuts de préparation pour le restaurateur (À préparer, En cours, En livraison).
    * `livraison.php` : Interface mobile dédiée au livreur avec actions rapides (Appel, GPS).
* **Logique Serveur (`/verif`)** :
    * `verification_connexion.php` & `verification_inscription.php` : Gestion de l'authentification et de la création de compte.
    * `ajouter_panier.php` : Script de gestion des quantités en session.
    * `validation_commande.php` : Traitement post-paiement et enregistrement de la commande.

### Stockage des Données (`/data`)
* `utilisateurs.json` : Base de données des comptes, rôles (client, admin, livreur, restaurateur) et points de fidélité.
* `plats.json` & `menus.json` : Catalogue complet des produits et compositions de menus.
* `commandes.json` : Registre centralisé du suivi des commandes et de leurs statuts.
* `avis.json` & `paiement.json` : Stockage des retours clients et des transactions validées.

---

## 🛠️ Problèmes connus & Solutions (Phase 2)

Le passage au dynamique a présenté plusieurs défis techniques qui ont été résolus durant le développement :

### 1. Synchronisation et Environnement
* [cite_start]**Problème :** Des soucis de synchronisation entre VS Code et le navigateur faisaient apparaître les fichiers HTML comme vides[cite: 39, 40].
* [cite_start]**Solution :** Création d'un dossier local propre (`Projet_Creative_Yumland`) avec activation de l'enregistrement automatique (Auto-Save) pour rétablir le lien direct avec le navigateur[cite: 50, 51].

### 2. Ergonomie et Responsive (Interface Livreur)
* [cite_start]**Problème :** Difficulté à adapter la taille des boutons pour un usage sur smartphone avec de "gros gants"[cite: 41, 42].
* [cite_start]**Solution :** Utilisation de la propriété `box-sizing: border-box`, de largeurs en pourcentage (`90%`) et de bordures transparentes pour harmoniser la taille des éléments cliquables[cite: 52, 53].

### 3. Affichage Dynamique des Produits
* [cite_start]**Problème :** Le plat "Croissant Viande" ne s'affichait pas sur la carte[cite: 88, 89].
* [cite_start]**Solution :** Harmonisation de la nomenclature des catégories entre le fichier `plats.json` et les filtres codés en PHP[cite: 92].

### 4. Consultation des Profils par l'Administrateur
* [cite_start]**Problème :** Conflit entre la session de l'admin et l'ID de l'utilisateur ciblé lors du clic sur "Voir Profil"[cite: 97, 98].
* [cite_start]**Solution :** Restructuration de la logique dans `profil.php` pour donner la priorité à l'identifiant transmis par `$_GET['id']` lorsque le rôle actif est "admin"[cite: 100].

### 5. API de Paiement CYBank (Point d'attention)
* [cite_start]**Problème :** Erreur "Code vendeur inconnu" et anomalie de clé de contrôle (Hash MD5) lors de la validation du panier[cite: 83].
* [cite_start]**Statut :** Malgré l'utilisation du code vendeur "TEST" et des tentatives de correction du calcul de hachage, la validation automatique reste à finaliser en raison de conflits de sécurité sur l'URL de retour[cite: 84, 85, 86].

---

## 🚀 Installation et Utilisation

Cette section détaille la procédure pour installer et lancer l'application localement en utilisant un environnement serveur de type WAMP ou MAMP.

### 📋 1. Prérequis

Avant de commencer, assurez-vous d'avoir installé l'un des logiciels suivants selon votre système d'exploitation :
* **Windows :** [WAMP Server](https://www.wampserver.com/)
* **macOS :** [MAMP](https://www.mamp.info/)
* **Navigateur :** Un navigateur moderne (Chrome, Firefox, Edge ou Safari) pour le rendu HTML/CSS.

---

### ⚙️ 2. Mise en place des fichiers

Pour que le serveur puisse interpréter le projet, les fichiers doivent être placés dans le dossier "racine" du logiciel :

1.  **Localiser le dossier cible :**
    * Sous **WAMP** : `C:\wamp64\www\`
    * Sous **MAMP** : `/Applications/MAMP/htdocs/`
2.  **Copier le projet :** Créez un dossier nommé `projet_phase2` dans ce répertoire et déposez-y tous vos fichiers (HTML, CSS, PHP, etc.).

---

### 🏃 3. Lancement de l'Application

Une fois les services activés, vous pouvez accéder à l'application via votre navigateur :

| Environnement | URL d'accès |
| :--- | :--- |
| **WAMP / MAMP** | `http://localhost/projet_phase2/` |

---

### ✅ 4. Vérification du rendu

* **HTML/CSS :** Si la mise en page s'affiche correctement, le serveur sert bien les fichiers statiques.
* **PHP/MySQL :** Testez une interaction (formulaire ou affichage de données) pour confirmer que la connexion à la base de données est active.

---

*© 2026 Flagrant Délice - CY Tech*
