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
- **Phase 2 : Dynamisation Serveur (PHP / JSON)** - *Terminée* ✅
- **Phase 3 : Dynamisation Client (JavaScript / AJAX)** - *Terminée* ✅
- **Phase 4 : Bonnes Pratiques & Sécurité** - *Terminée* ✅

---

## 🎨 Charte Graphique

L'identité visuelle repose sur un contraste fort pour souligner le côté "décalé" du restaurant :
* **Bleu Cyan (`#00a8e8`)** : Couleur principale (fonds, bordures, titres).
* **Rouge Vif (`#e60012`)** : Couleur d'accentuation (boutons, actions importantes, alertes).
* **Typographie** : Utilisation des polices `Impact` et `Arial Black` pour les titres afin de donner un effet "Headline".
* **Mode Sombre** : Une charte alternative activable via un bouton sur toutes les pages, mémorisée par cookie.

---

## 📂 Contenu de la Phase 1

La phase 1 se concentre exclusivement sur l'intégration graphique et la structure des pages (Front-End statique). Aucune base de données n'est connectée pour le moment.

### Arborescence des fichiers

* **Partie Client :**
    * `accueil.html` : Page d'accueil (vitrine des plats populaires et barre de recherche).
    * `presentation.html` : La carte complète avec filtres visuels (catégories, allergènes).
    * `inscription.html` : Formulaire de création de compte complet.
    * `connexion.html` : Page d'identification.
    * `profil.html` : Espace client (informations, fidélité, historique des commandes).
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
* **Formulaires :** Les formulaires sont visuels. Ils redirigent vers les pages cibles mais ne traitent pas encore les données (fait en Phase 2 avec PHP).

---

## 📂 Contenu de la Phase 2

La Phase 2 marque le passage d'un site statique à une application web dynamique pilotée par le serveur via PHP et des fichiers de données structurés.

### Architecture Dynamique (PHP)
* **Pages Clients** :
    * `accueil.php` : Page d'accueil gérant l'état de connexion.
    * `presentation.php` : Affichage dynamique du catalogue à partir de `plats.json` avec gestion du panier.
    * `panier.php` : Tunnel de commande complet (mode de consommation, intégration API CYBank).
    * `profil.php` : Espace personnel (points de fidélité, historique des commandes).
    * `notation.php` : Système de notation des livraisons et des repas.
* **Interfaces Staff & Admin** :
    * `admin.php` : Dashboard de gestion de tous les utilisateurs avec outils de debug.
    * `commandes.php` : Gestion des statuts de préparation pour le restaurateur.
    * `livraison.php` : Interface mobile dédiée au livreur (appel, GPS).
* **Logique Serveur (`/verif`)** :
    * `verification_connexion.php` & `verification_inscription.php` : Authentification et création de compte.
    * `ajouter_panier.php` : Gestion des quantités en session.
    * `validation_commande.php` : Traitement post-paiement et enregistrement de la commande.

### Stockage des Données (`/data`)
* `utilisateurs.json` : Comptes, rôles (client, admin, livreur, restaurateur) et points de fidélité.
* `plats.json` & `menus.json` : Catalogue complet des produits et compositions de menus.
* `commandes.json` : Registre centralisé du suivi des commandes et de leurs statuts.
* `avis.json` & `paiement.json` : Retours clients et transactions validées.

---

## 🛠️ Problèmes connus & Solutions (Phase 2)

### 1. Environnement et Synchronisation
* **Problème :** Fichiers apparaissant vides dans le navigateur lors de l'utilisation de dossiers distants.
* **Solution :** Migration vers un dossier local dédié avec activation de l'Auto-Save.

### 2. Affichage Dynamique
* **Problème :** Erreur d'affichage sur certains produits malgré des données correctes.
* **Solution :** Harmonisation de la nomenclature des catégories entre `plats.json` et les filtres PHP.

### 3. Gestion des Profils (Admin)
* **Problème :** Conflit entre la session de l'administrateur et l'affichage d'un profil client.
* **Solution :** Priorité donnée à `$_GET['id']` lorsque le rôle actif est admin.

### 4. Intégration Paiement CYBank
* **Problème :** Erreurs de hachage MD5 causées par une modification de l'URL de retour en JavaScript après le calcul du hash en PHP.
* **Statut :** Résolu en Phase 3 (voir ci-dessous).

---

## 📂 Contenu de la Phase 3

La Phase 3 introduit JavaScript et la manipulation du DOM pour rendre l'interface pleinement interactive, sans rechargement de page.

### Fonctionnalités JavaScript (`script.js`)

* **Mode Sombre / Clair :** Changement dynamique du fichier CSS sans rechargement, choix mémorisé dans un cookie 30 jours.
* **Validation des formulaires côté client :** Vérification des champs (email, téléphone, mot de passe, âge) avant envoi au serveur, avec messages d'erreur en temps réel.
* **Affichage / masquage des mots de passe :** Icône œil sur les champs de saisie.
* **Compteurs de caractères en temps réel :** Sur tous les champs limités en taille.
* **Filtres asynchrones (Fetch / AJAX) :** Filtrage des plats par catégorie, allergène et recherche textuelle sans rechargement de la page de présentation.
* **Modification de profil asynchrone :** Les informations personnelles sont mises à jour et envoyées au serveur au clic sans rechargement.
* **Gestion des commandes (restaurateur) :** Changement de statut des commandes en AJAX.
* **Validation livraison (livreur) :** Confirmation ou abandon d'une livraison en AJAX.
* **Blocage / déblocage utilisateur (admin) :** Action asynchrone qui ferme immédiatement la session de l'utilisateur ciblé.
* **Polling de sécurité :** Vérification toutes les 30 secondes si le compte connecté a été bloqué, déconnexion automatique si c'est le cas.

### Nouveaux scripts serveur (`/verif`)
* `maj_profil.php` : Traitement asynchrone des modifications de profil.
* `maj_statut_commande.php` : Mise à jour du statut d'une commande.
* `maj_livraison.php` : Validation ou abandon d'une livraison.
* `bloquer_utilisateur.php` : Blocage / déblocage d'un compte en temps réel.
* `maj_fidelite.php` : Modification du statut et des points de fidélité.
* `filtrer_plats.php` : Retourne les plats filtrés en HTML pour les requêtes asynchrones.
* `verifier_blocage.php` : Vérifie si le compte courant est bloqué (utilisé par le polling).

---

## 🛠️ Problèmes connus & Solutions (Phase 3)

### 1. Déconnexion immédiate de l'utilisateur bloqué
* **Problème :** La requête Fetch détruisait la session de l'admin et non celle de l'utilisateur ciblé.
* **Solution :** Mise en place d'un polling asynchrone côté client toutes les 30 secondes. Dès que le statut passe à "bloqué" dans le JSON, la session du client est fermée et il est redirigé.

### 2. Clignotement visuel (FOUC)
* **Problème :** Le CSS du mode sombre était appliqué après le chargement, causant un flash.
* **Solution :** Lecture du cookie directement en PHP en début de page pour injecter le bon fichier CSS avant le rendu.

### 3. Résolution définitive de CYBank
* **Problème :** Le JavaScript du panier modifiait l'URL de retour après que PHP avait calculé le hash, invalidant la signature.
* **Solution :** Suppression de toute modification JavaScript sur l'URL. Toutes les variables sont figées en PHP avant la redirection vers l'API.

---

## 📂 Contenu de la Phase 4

La Phase 4 finalise l'application en appliquant les bonnes pratiques de développement web : sécurité, accessibilité et correction des bugs restants. Une fonctionnalité innovante a également été ajoutée.

### Sécurité
* **Protection des données :** Ajout d'un fichier `data/.htaccess` bloquant tout accès direct aux fichiers JSON depuis le navigateur (erreur 403). Les données restent accessibles uniquement par le code PHP côté serveur.
* **Contrôle des accès :** Tous les scripts `/verif` vérifient systématiquement la session et le rôle avant toute action.
* **Protection XSS :** Toutes les données affichées sont nettoyées avec `htmlspecialchars()`.
* **Intégrité des profils :** La modification de profil utilise toujours l'ID de session, jamais l'ID du formulaire.

### Accessibilité
* Ajout de l'attribut `aria-label` sur les boutons sans texte (bouton de thème).
* Ajout de l'attribut `aria-current="page"` sur les liens actifs de navigation.

### Corrections de bugs
* **Page livraison :** Correction des erreurs PHP sur les commandes sans adresse (commandes sur place ou à emporter).
* **Navigation admin :** Les administrateurs, restaurateurs et livreurs voient désormais leur lien dédié sur les pages publiques (accueil, carte) au lieu des boutons Connexion/Inscription.
* **Lien Notation :** Ajout du lien vers la page de notation dans l'accès rapide de l'administrateur.

### Fonctionnalité Innovante — 🎲 Plat Aléatoire
Un bouton **"🎲 PLAT ALÉATOIRE"** a été ajouté sur la page de la carte, visible uniquement pour les clients connectés. En un clic, un plat est tiré au sort parmi l'ensemble du catalogue et ajouté directement au panier. Un message de confirmation affiche le nom du plat sélectionné.

---

## 🚀 Installation et Utilisation

### 📋 Prérequis

* **Windows :** [WAMP Server](https://www.wampserver.com/)
* **macOS :** [MAMP](https://www.mamp.info/)
* **Navigateur :** Chrome, Firefox, Edge ou Safari.

### ⚙️ Mise en place

1. Localiser le dossier racine du serveur :
    * **WAMP :** `C:\wamp64\www\`
    * **MAMP :** `/Applications/MAMP/htdocs/`
2. Copier le dossier du projet dans ce répertoire.
3. Démarrer les services Apache du logiciel.

### 🏃 Accès

| Environnement | URL |
| :--- | :--- |
| **WAMP / MAMP** | `http://localhost/[nom_du_dossier]/` |

### ✅ Comptes de test disponibles

| Rôle | Email | Mot de passe |
| :--- | :--- | :--- |
| **Client** | tristan.douille@email.com | Unmotdepasse13sécurisé! |
| **Admin** | tarko@email.com | TarkoOcho8 |
| **Restaurateur** | mario@email.com | Yahou005 |
| **Livreur** | paul@email.com | Onepiece1 |

---

*© 2026 Flagrant Délice - CY Tech*
