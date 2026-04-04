<?php
// verif/verification_inscription.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. SÉCURITÉ : Vérification que les champs obligatoires ne sont pas vides
    // (Au cas où l'utilisateur aurait trafiqué le HTML pour enlever les "required")
    if (empty($_POST['email']) || empty($_POST['mdp']) || empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['telephone'])) {
        header("Location: ../inscription.php?erreur=champs_vides");
        exit();
    }

    $fichier = '../data/utilisateurs.json';

    if (file_exists($fichier)) {
        $contenu = file_get_contents($fichier);
    } else {
        $contenu = '[]';
    }

    $utilisateurs = json_decode($contenu, true);

    // 2. SÉCURITÉ : Nettoyage absolu de TOUTES les chaînes de texte (Faille XSS)
    // trim() enlève les espaces au début et à la fin.
    // htmlspecialchars() transforme les chevrons < et > en texte inoffensif.
    $email_saisi  = htmlspecialchars(trim($_POST['email']));
    $nom_propre   = htmlspecialchars(trim($_POST['nom']));
    $prenom_propre = htmlspecialchars(trim($_POST['prenom']));
    $adresse_propre = htmlspecialchars(trim($_POST['adresse']));
    $complement_propre = htmlspecialchars(trim($_POST['complement_adresse']));
    $cp_propre    = htmlspecialchars(trim($_POST['code_postal']));
    $ville_propre = htmlspecialchars(trim($_POST['ville']));
    $pref_alim_propre = htmlspecialchars(trim($_POST['preferences_alimentaires']));

    // Nettoyage du téléphone
    $tel_propre = str_replace(' ', '', $_POST['telephone']);

    $type_erreur = "";

    // Vérification du format du téléphone
    if (strlen($tel_propre) != 10 || !ctype_digit($tel_propre) || $tel_propre[0] != '0') {
        $type_erreur = "tel_invalide";
    } else {
        // Vérification des doublons avec l'email nettoyé
        if (!empty($utilisateurs)) {
            foreach ($utilisateurs as $user) {
                if ($user['login'] == $email_saisi) {
                    $type_erreur = "email_existant";
                    break;
                }
                if ($user['informations']['telephone'] == $tel_propre) {
                    $type_erreur = "tel_existant";
                    break;
                }
            }
        }
    }

    if ($type_erreur != "") {
        header("Location: ../inscription.php?erreur=" . $type_erreur);
        exit();
    }

    // Détermination de l'ID
    $nouvel_id = 1;
    if (!empty($utilisateurs)) {
        $dernier_user = end($utilisateurs);
        $nouvel_id = $dernier_user['id_utilisateur'] + 1;
    }

    $preferences_contact = isset($_POST['offres']) ? $_POST['offres'] : [];

    // 3. SÉCURITÉ : Hachage du mot de passe
    // Transforme "mdp123" en "$2y$10$wTfV6/b..."
    $mdp_securise = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

    // Création du nouvel utilisateur avec les variables PROPRES
    $nouvel_utilisateur = [
        "id_utilisateur" => $nouvel_id,
        "login" => $email_saisi,
        "mot_de_passe" => $mdp_securise, // Mot de passe crypté !
        "role" => "client",
        "informations" => [
            "nom" => $nom_propre,
            "prenom" => $prenom_propre,
            "naissance" => $_POST['date_naissance'],
            "adresse" => [
                "rue" => $adresse_propre,
                "complement" => $complement_propre,
                "code_postal" => $cp_propre,
                "ville" => $ville_propre
            ],
            "telephone" => $tel_propre,
            "preferences_alimentaires" => $pref_alim_propre,
            "preferences_contact" => $preferences_contact
        ],
        "fidelite" => [
            "statut" => "SUSPECT CULINAIRE",
            "points" => 0
        ],
        "dates" => [
            "inscription" => date("Y-m-d\TH:i:s"),
            "derniere_connexion" => date("Y-m-d\TH:i:s")
        ]
    ];

    // Ajout au tableau
    $utilisateurs[] = $nouvel_utilisateur;

    // 4. SÉCURITÉ : Utilisation de LOCK_EX pour éviter la corruption du fichier
    file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

    header("Location: ../connexion.php?succes=1");
    exit();
}
