<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Vérification des champs vides obligatoires
    if (
        empty($_POST['email']) || empty($_POST['mdp']) || empty($_POST['nom']) ||
        empty($_POST['prenom']) || empty($_POST['telephone']) || empty($_POST['date_naissance']) ||
        empty($_POST['adresse']) || empty($_POST['code_postal']) || empty($_POST['ville']) ||
        empty($_POST['age'])
    ) {

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

    // Nettoyage
    $email_saisi  = htmlspecialchars(trim($_POST['email']));
    $tel_propre = str_replace(' ', '', $_POST['telephone']);
    $date_saisie = $_POST['date_naissance'];
    $mdp_saisi = $_POST['mdp'];

    $type_erreur = "";

    // Verif date de naissance
    $annee_saisie = (int)date('Y', strtotime($date_saisie));
    $date_aujourdhui = date('Y-m-d');

    if ($annee_saisie < 1900 || $date_saisie > $date_aujourdhui) {
        $type_erreur = "date_invalide";
    }

    //mdp avec 8 caractères minimum
    elseif (strlen($mdp_saisi) < 8) {
        $type_erreur = "mdp_invalide";
    }

    // Vérification du format du téléphone
    elseif (strlen($tel_propre) != 10 || !ctype_digit($tel_propre) || $tel_propre[0] != '0') {
        $type_erreur = "tel_invalide";
    }
    // Vérification des doublons (Email et Téléphone)
    else {
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

    // Redirection en cas d'erreur
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

    if (isset($_POST['offres'])) {
        $preferences_contact = $_POST['offres'];
    } else {
        $preferences_contact = [];
    }

    // Création de l'utilisateur
    $nouvel_utilisateur = [
        "id_utilisateur" => $nouvel_id,
        "login" => $email_saisi,
        "mot_de_passe" => $mdp_saisi,
        "role" => "client",
        "informations" => [
            "nom" => htmlspecialchars(trim($_POST['nom'])),
            "prenom" => htmlspecialchars(trim($_POST['prenom'])),
            "naissance" => $date_saisie,
            "adresse" => [
                "rue" => htmlspecialchars(trim($_POST['adresse'])),
                "complement" => htmlspecialchars(trim($_POST['complement_adresse'])),
                "code_postal" => htmlspecialchars(trim($_POST['code_postal'])),
                "ville" => htmlspecialchars(trim($_POST['ville']))
            ],
            "telephone" => $tel_propre,
            "preferences_alimentaires" => htmlspecialchars(trim($_POST['preferences_alimentaires'])),
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

    $utilisateurs[] = $nouvel_utilisateur;

    // Sauvegarde
    file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

    header("Location: ../connexion.php?succes=1");
    exit();
}
