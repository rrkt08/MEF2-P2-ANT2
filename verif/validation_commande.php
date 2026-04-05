<?php
session_start();
require_once('getapikey.php');

// Récupération des données renvoyées par CYBank
$status = $_GET['status'] ?? '';
$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$control_banque = $_GET['control'] ?? '';

// Récupération des données personnalisées ajoutées dans l'URL de retour
$mode_conso = $_GET['mode'] ?? 'livraison';
$type_preparation = $_GET['type_preparation'] ?? 'immediate';
$date_commande_choisie = $_GET['date_commande'] ?? '';

$api_key = getAPIKey($vendeur);

// Vérification de sécurité 
$check_hash = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $status . "#");

if ($status == 'accepted' && $check_hash == $control_banque) {

    // Paiement réussit, enregistrement de la commande
    $id_client = $_SESSION['id_utilisateur'];

    // Charger les plats pour le catalogue
    $plats_data = json_decode(file_get_contents('../data/plats.json'), true);
    $catalogue = [];
    foreach ($plats_data as $p) {
        $catalogue[$p['id_plat']] = $p;
    }

    // Préparer la liste des articles
    $liste_articles = [];
    foreach ($_SESSION['panier'] as $id => $qte) {
        $liste_articles[] = [
            "type" => "plat",
            "id_article" => $id,
            "quantite" => $qte,
            "options_choisies" => []
        ];
    }

    // Récupération de l'adresse du client 
    $adresse_client = ["rue" => "Non renseignée", "code_postal" => "", "ville" => "", "complement" => ""];
    $users = json_decode(file_get_contents('../data/utilisateurs.json'), true);
    foreach ($users as $u) {
        if ($u['id_utilisateur'] == $id_client) {
            $adresse_client = $u['informations']['adresse'];
            break;
        }
    }

    // Gestion statut et date
    if ($type_preparation == 'plustard' && !empty($date_commande_choisie)) {
        $statut_preparation = "EN ATTENTE";
        $date_heure = date("Y-m-d\TH:i:s", strtotime($date_commande_choisie));
    } else {
        $statut_preparation = "A PREPARER";
        $date_heure = date("Y-m-d\TH:i:s");
    }

    if ($mode_conso == 'sur_place') $lieu_consommation = "sur place";
    elseif ($mode_conso == 'emporter') $lieu_consommation = "a emporter";
    else $lieu_consommation = "livraison";

    // Chargement et maj des commandes
    $fichier_commandes = '../data/commandes.json';
    $commandes = json_decode(file_get_contents($fichier_commandes), true) ?? [];

    $commandes[] = [
        "id_commande" => "CMD-" . str_pad(count($commandes) + 1, 3, "0", STR_PAD_LEFT),
        "id_client" => $id_client,
        "id_livreur" => null,
        "date_heure" => $date_heure,
        "statut_preparation" => $statut_preparation,
        "statut_paiement" => "paye",
        "lieu_consommation" => $lieu_consommation,
        "adresse_livraison" => ($mode_conso == 'livraison') ? $adresse_client : null,
        "liste_articles" => $liste_articles,
        "prix_total" => (float)$montant
    ];

    file_put_contents($fichier_commandes, json_encode($commandes, JSON_PRETTY_PRINT));

    // Vider le panier après commande
    unset($_SESSION['panier']);

    // Redirection du client vers son profil
    header("Location: ../profil.php?commande=succes");
    exit();
} else {
    // Si le paiement est refusé ou le hash incorrect
    header("Location: ../panier.php?erreur=paiement_refuse");
    exit();
}
