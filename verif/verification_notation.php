<?php
session_start();

if (!isset($_SESSION['utilisateur_connecte'])) {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // récup + cleanup des données
    $id_commande = htmlspecialchars(trim($_POST['id_commande']));
    $note_livraison = (int)$_POST['note-livraison'];
    $note_repas = (int)$_POST['note-repas'];
    $commentaire = htmlspecialchars(trim($_POST['commentaire']));
    $id_client = $_SESSION['id_utilisateur'];

    // on vérifie que la cmd existe + appartient au client + est livrée
    $fichier_commandes = '../data/commandes.json';
    $commandes = json_decode(file_get_contents($fichier_commandes), true);

    $commande_valide = false;
    $index_commande = -1;

    for ($i = 0; $i < count($commandes); $i = $i + 1) {
        if ($commandes[$i]['id_commande'] == $id_commande) {
            if ($commandes[$i]['id_client'] == $id_client && $commandes[$i]['statut_preparation'] == "LIVRÉ") {
                // déjà notée ?
                if (isset($commandes[$i]['deja_note']) && $commandes[$i]['deja_note'] == true) {
                    header("Location: ../profil.php?erreur_avis=deja_note");
                    exit();
                }
                $commande_valide = true;
                $index_commande = $i;
            }
            break;
        }
    }

    if (!$commande_valide) {
        header("Location: ../profil.php?erreur_avis=invalide");
        exit();
    }

    $nouvel_avis = [
        "id_commande" => $id_commande,
        "id_client" => $id_client,
        "note_livraison" => $note_livraison,
        "note_repas" => $note_repas,
        "commentaire" => $commentaire,
        "date_avis" => date("Y-m-d H:i:s")
    ];

    $fichier_avis = '../data/avis.json';

    if (file_exists($fichier_avis)) {
        $contenu = file_get_contents($fichier_avis);
        $tous_les_avis = json_decode($contenu, true);
        if ($tous_les_avis === null) {
            $tous_les_avis = [];
        }
    } else {
        $tous_les_avis = [];
    }

    $tous_les_avis[] = $nouvel_avis;
    file_put_contents($fichier_avis, json_encode($tous_les_avis, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // marque la cmd comme notée (pour pas qu'on note deux fois)
    $commandes[$index_commande]['deja_note'] = true;
    file_put_contents($fichier_commandes, json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    header("Location: ../profil.php?succes_avis=1");
    exit();
} else {
    // accès direct => on dégage
    header("Location: ../accueil.php");
    exit();
}
