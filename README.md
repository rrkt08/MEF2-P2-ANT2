# 🍔🍩 Flagrant Délice - Projet Creative Yumland (Pré-ing 2 CY Tech 2025-26)

> **"Mélanges Interdits, Saveurs Criminelles."**

Bienvenue sur le dépôt officiel du projet **Flagrant Délice**.

Le concept ? Un restaurant audacieux spécialisé dans les **"crimes culinaires"** : des mélanges interdits mais délicieux comme le Burger Donut, la Pizza Hawaïenne ou les Frites au Chocolat.

## Ce projet est réalisé par le trinôme suivant :
* **Ahmed Eish**
* **Naim Mohammed**
* **Tarek Itim**
j
---

## État d'Avancement du Projet

- **Phase 1 : Interface Statique (HTML5 / CSS3)** - *Terminée* ✅
- **Phase 2 : Dynamisation Serveur (PHP / MySQL)** - *En cours* 🚧
- **Phase 3 : Dynamisation Client (JavaScript / AJAX)** - *À venir* ⏳
- **Phase 4 : Bonnes Pratiques & Optimisation** - *À venir* ⏳

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

## 🛠️ Problèmes connus & Solutions (Phase 1)

* **Responsive Livreur :** L'interface `livraison.html` a été spécifiquement codée avec `box-sizing: border-box` et des largeurs en pourcentage pour s'adapter aux écrans mobiles et faciliter le clic (contrainte des gants).
* **Formulaires :** Les formulaires (recherche, filtres, inscription) sont visuels. Ils redirigent vers les pages cibles via `action="..."` mais ne traitent pas encore les données (cela sera fait en Phase 2 avec PHP).

---

*© 2026 Flagrant Délice - CY Tech*
