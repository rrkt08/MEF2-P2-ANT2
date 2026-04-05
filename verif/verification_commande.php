<?php
session_start();

if (!isset($_SESSION['utilisateur_connecte'])) {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fichier_commandes = '../data/commandes.json';
    $commandes = [];

    if (file_exists($fichier_commandes)) {
        $contenu = file_get_contents($fichier_commandes);
        $commandes = json_decode($contenu, true);
        if ($commandes === null) {
            $commandes = [];
        }
    }

    $fichier_utilisateurs = '../data/utilisateurs.json';
    $utilisateurs = [];

    if (file_exists($fichier_utilisateurs)) {
        $contenu_users = file_get_contents($fichier_utilisateurs);
        $utilisateurs = json_decode($contenu_users, true);
        if ($utilisateurs === null) {
            $utilisateurs = [];
        }
    }

    $id_client = $_SESSION['id_utilisateur'];
    $adresse_livraison = null;
    $lieu_consommation = $_POST['lieu_consommation'];

    if ($lieu_consommation == "livraison") {
        foreach ($utilisateurs as $user) {
            if ($user['id_utilisateur'] == $id_client) {
                $adresse_livraison = $user['informations']['adresse'];
                break;
            }
        }
    }

    $nouveau_numero = 1;
    if (!empty($commandes)) {
        $derniere_commande = end($commandes);
        $dernier_id = $derniere_commande['id_commande'];
        $partie_numerique = str_replace("CMD-", "", $dernier_id);
        $nouveau_numero = (int)$partie_numerique + 1;
    }

    $format_numero = str_pad($nouveau_numero, 3, "0", STR_PAD_LEFT);
    $nouvel_id_commande = "CMD-" . $format_numero;

    $date_heure_demande = date("Y-m-d\TH:i:s");
    $statut_preparation = "A PREPARER";

    if (isset($_POST['type_preparation'])) {
        if ($_POST['type_preparation'] == "plustard") {
            if (!empty($_POST['date_commande'])) {
                $date_heure_demande = $_POST['date_commande'];

                $timestamp_demande = strtotime($date_heure_demande);
                $timestamp_actuel = time();
                $limite_max = strtotime('+2 days', $timestamp_actuel);

                if ($timestamp_demande < $timestamp_actuel) {
                    header("Location: ../validation.php?erreur=date_invalide");
                    exit();
                }

                if ($timestamp_demande > $limite_max) {
                    header("Location: ../validation.php?erreur=date_invalide");
                    exit();
                }

                $delai_secondes = $timestamp_demande - $timestamp_actuel;

                if ($delai_secondes > 2700) {
                    $statut_preparation = "EN ATTENTE";
                }
            }
        }
    }

    $nouvelle_commande = [
        "id_commande" => $nouvel_id_commande,
        "id_client" => $id_client,
        "liste_articles" => $_SESSION['panier'],
        "lieu_consommation" => $lieu_consommation,
        "adresse_livraison" => $adresse_livraison,
        "statut_paiement" => "attente",
        "date_heure" => $date_heure_demande,
        "statut_preparation" => $statut_preparation,
        "id_livreur" => null,
        "prix_total" => $_SESSION['total_panier']
    ];

    $commandes[] = $nouvelle_commande;

    file_put_contents($fichier_commandes, json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    unset($_SESSION['panier']);
    unset($_SESSION['total_panier']);

    header("Location: ../profil.php");
    exit();
} else {
    header("Location: ../accueil.php");
    exit();
}
