<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Vérification des champs vides obligatoires
    if (empty($_POST['email']) || empty($_POST['mdp'])) {
        header("Location: ../connexion.php?erreur=vide");
        exit();
    }

    $email_saisi = $_POST['email'];
    $mdp_saisi = $_POST['mdp'];

    $fichier = '../data/utilisateurs.json';

    if (file_exists($fichier)) {
        $contenu = file_get_contents($fichier);
    } else {
        $contenu = '[]';
    }

    $utilisateurs = json_decode($contenu, true);

    $utilisateur_trouve = false;
    $profil_utilisateur = null;

    // Recherche de l'utilisateur
    if (!empty($utilisateurs)) {
        foreach ($utilisateurs as $user) {
            if ($user['login'] == $email_saisi && $user['mot_de_passe'] == $mdp_saisi) {
                $utilisateur_trouve = true;
                $profil_utilisateur = $user;
                break;
            }
        }
    }

    if ($utilisateur_trouve == true) {

        // Sauvegarde des infos de l'utilisateur
        $_SESSION['utilisateur_connecte'] = true;
        $_SESSION['id_utilisateur'] = $profil_utilisateur['id_utilisateur'];
        $_SESSION['role'] = $profil_utilisateur['role'];
        $_SESSION['prenom'] = $profil_utilisateur['informations']['prenom'];
        $_SESSION['nom'] = $profil_utilisateur['informations']['nom'];

        // Redirection selon le rôle
        if ($_SESSION['role'] == "admin") {
            header("Location: ../admin.php");
        } elseif ($_SESSION['role'] == "restaurateur") {
            header("Location: ../commandes.php");
        } elseif ($_SESSION['role'] == "livreur") {
            header("Location: ../livraison.php");
        } else {
            header("Location: ../profil.php");
        }
        exit();
    } else {
        // Email ou mdp faux
        header("Location: ../connexion.php?erreur=identifiants");
        exit();
    }
}
