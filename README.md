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

## 🚀 Installation et Utilisation

Puisque le projet est actuellement en **Phase 1 (Statique)**, aucun serveur local (WAMP/XAMPP) n'est strictement nécessaire pour visualiser le design, mais il sera requis pour la Phase 2.

1.  **Télécharger le dépôt :**
    ```bash
    [https://github.com/votre-user/Projet_Creative_Yumland.git](https://github.com/votre-user/Projet_Creative_Yumland.git)
    ```
2.  **Lancer le site :**
    * Ouvrez simplement le fichier `accueil.html` dans votre navigateur web préféré (Chrome, Firefox, Edge).
3.  **Navigation :**
    * Vous pouvez naviguer entre les pages via les menus.
    * Pour accéder aux interfaces "Staff", passez par le lien caché ou via la page `admin.html` (Section "Accès Rapide Debug").

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

L'implémentation de la logique serveur a nécessité de résoudre des problématiques de sécurité et de persistance des données.

* **Sécurisation des accès par rôle** :
    * **Problème** : Risque d'accès non autorisé aux interfaces "Staff" (Cuisine, Livraison, Admin) par simple saisie de l'URL.
    * **Solution** : Mise en place de vérifications de session systématiques au début de chaque fichier PHP sensible, redirigeant l'utilisateur vers `connexion.php` s'il ne possède pas le rôle requis.
* **Fiabilité des transactions CYBank** :
    * **Problème** : Risque d'altération du montant ou de l'URL de retour lors du processus de paiement.
    * **Solution** : Utilisation d'un **hash de contrôle MD5** incluant l'API Key et les paramètres de transaction, vérifié par le script `validation_commande.php` dès le retour de la banque.
* **Robustesse de la lecture JSON** :
    * **Problème** : Erreur fatale potentielle si un fichier de données (`.json`) est absent ou corrompu lors du chargement.
    * **Solution** : Utilisation de `file_exists()` couplé à une initialisation par défaut avec un tableau vide `[]` en cas d'erreur de décodage.
* **Ergonomie Mobile Staff** :
    * **Problème** : Difficulté de manipulation de l'interface livraison avec des "gros gants" sur smartphone.
    * **Solution** : Conservation de boutons larges (90% de largeur) et simplification des actions via des formulaires directs pour valider la livraison.
* **Validation des formulaires** :
    * **Problème** : Inscription de doublons (e-mail ou téléphone déjà existants) ou données vides.
    * **Solution** : Scripts de vérification backend parcourant le fichier `utilisateurs.json` avant toute nouvelle insertion, avec renvoi de codes d'erreurs explicites dans l'URL.

---


*© 2026 Flagrant Délice - CY Tech*
