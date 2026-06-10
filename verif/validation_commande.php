<?php
session_start();
require_once('getapikey.php');

// données renvoyées par CYBank après le paiement
$status = $_GET['status'] ?? '';
$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$control_banque = $_GET['control'] ?? '';

// on récupère les choix mis en session avant le paiement
$mode_conso = $_SESSION['paiement_mode'] ?? 'livraison';
$type_preparation = $_SESSION['paiement_prep'] ?? 'immediate';
$date_commande_choisie = $_SESSION['paiement_date'] ?? '';

// cas d'une modification de commande déjà existante
$id_cmd_modif = $_GET['cmd_modif'] ?? '';

$api_key = getAPIKey($vendeur);

// même url de retour que dans preparer_paiement.php
// CYBank nous renvoie dessus avec le status et le hash pour qu'on puisse vérifier
$dossier = dirname($_SERVER['PHP_SELF']);
$url_retour_attendue = "http://" . $_SERVER['HTTP_HOST'] . $dossier . "/validation_commande.php";
if ($id_cmd_modif != "") {
    $url_retour_attendue = $url_retour_attendue . "?cmd_modif=" . urlencode($id_cmd_modif);
}

// on recalcule le hash de notre côté pour vérifier qu'il correspond à celui de la banque
$check_hash = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $status . "#");

if ($status == 'accepted' && $check_hash == $control_banque) {

    // cas où c'est une modification de commande existante
    if ($id_cmd_modif != '') {
        $fichier_commandes = '../data/commandes.json';
        $commandes = json_decode(file_get_contents($fichier_commandes), true);

        for ($i = 0; $i < count($commandes); $i = $i + 1) {
            if ($commandes[$i]['id_commande'] == $id_cmd_modif) {
                $commandes[$i]['statut_paiement'] = "paye";
                break;
            }
        }

        file_put_contents($fichier_commandes, json_encode($commandes, JSON_PRETTY_PRINT));
        header("Location: ../profil.php?modif_commande=succes");
        exit();
    }

    // cas normal : c'est une nouvelle commande
    $id_client = $_SESSION['id_utilisateur'];

    // on charge les plats pour avoir les détails de chaque article du panier
    $plats_data = json_decode(file_get_contents('../data/plats.json'), true);
    $catalogue = [];
    foreach ($plats_data as $p) {
        $catalogue[$p['id_plat']] = $p;
    }

    // construction de la liste des articles à partir du panier en session
    $liste_articles = [];
    foreach ($_SESSION['panier'] as $id => $qte) {
        $liste_articles[] = [
            "type" => "plat",
            "id_article" => $id,
            "quantite" => $qte,
            "options_choisies" => []
        ];
    }

    // on récupère l'adresse du client pour la livraison
    $adresse_client = ["rue" => "Non renseignée", "code_postal" => "", "ville" => "", "complement" => ""];
    $users = json_decode(file_get_contents('../data/utilisateurs.json'), true);
    foreach ($users as $u) {
        if ($u['id_utilisateur'] == $id_client) {
            $adresse_client = $u['informations']['adresse'];
            break;
        }
    }

    // le statut et la date dépendent du choix "immédiat" ou "pour plus tard"
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

    // on ajoute la commande dans le fichier json
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
        "prix_total" => (float)$montant,
        "deja_note" => false
    ];

    file_put_contents($fichier_commandes, json_encode($commandes, JSON_PRETTY_PRINT));

    // on vide le panier et les données de paiement stockées en session
    unset($_SESSION['panier']);
    unset($_SESSION['paiement_mode']);
    unset($_SESSION['paiement_prep']);
    unset($_SESSION['paiement_date']);

    header("Location: ../profil.php?commande=succes");
    exit();

} else {
    // le paiement a été refusé ou le hash ne correspond pas
    header("Location: ../panier.php?erreur=paiement_refuse");
    exit();
}
