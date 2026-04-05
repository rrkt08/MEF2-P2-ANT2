<?php
session_start();
require_once('getapikey.php');

// 1. Récupération des données renvoyées par CYBank
$status = $_GET['status'] ?? '';
$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$control_banque = $_GET['control'] ?? '';
$mode_conso = $_GET['mode'] ?? 'livraison';

$api_key = getAPIKey($vendeur);

// 2. Vérification de sécurité (Correction du # final selon la doc CYBank)
$check_hash = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $status . "#");

if ($status == 'accepted' && $check_hash == $control_banque) {
    
    // PAIEMENT RÉUSSI : On enregistre la commande
    $id_client = $_SESSION['id_utilisateur'];
    
    // Charger les plats pour le catalogue
    $plats_data = json_decode(file_get_contents('../data/plats.json'), true);
    $catalogue = [];
    foreach ($plats_data as $p) { $catalogue[$p['id_plat']] = $p; }

    // Préparer la liste des articles
    $liste_articles = [];
    foreach ($_SESSION['panier'] as $id => $qte) {
        $liste_articles[] = ["type" => "plat", "id_article" => $id, "quantite" => $qte, "options_choisies" => []];
    }

    // Récupérer l'adresse réelle du client dans utilisateurs.json
    $adresse_client = ["rue" => "Non renseignée", "code_postal" => "", "ville" => "", "complement" => ""];
    $users = json_decode(file_get_contents('../data/utilisateurs.json'), true);
    foreach ($users as $u) {
        if ($u['id_utilisateur'] == $id_client) {
            $adresse_client = $u['informations']['adresse'];
            break;
        }
    }

    // Charger et mettre à jour les commandes
    $fichier_commandes = '../data/commandes.json';
    $commandes = json_decode(file_get_contents($fichier_commandes), true) ?? [];

    $commandes[] = [
        "id_commande" => "CMD-" . str_pad(count($commandes) + 1, 3, "0", STR_PAD_LEFT),
        "id_client" => $id_client,
        "id_livreur" => null,
        "date_heure" => date("Y-m-d H:i:s"),
        "statut_preparation" => "A PREPARER",
        "statut_paiement" => "paye",
        "lieu_consommation" => $mode_conso,
        "adresse_livraison" => ($mode_conso == 'livraison') ? $adresse_client : "N/A",
        "liste_articles" => $liste_articles,
        "prix_total" => (float)$montant
    ];

    file_put_contents($fichier_commandes, json_encode($commandes, JSON_PRETTY_PRINT));
    
    // Vider le panier et rediriger
    unset($_SESSION['panier']); 
    header("Location: ../profil.php?succes_commande=1");

} else {
    // ÉCHEC : Retour au panier avec message d'erreur
    header("Location: ../panier.php?erreur_paiement=1");
}
exit();