<?php
session_start();

// Vérification que l'utilisateur est bien connecté
if (!isset($_SESSION['utilisateur_connecte'])) {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // récupèration et nettoyage des données envoyées par le formulaire
    $id_commande = htmlspecialchars(trim($_POST['id_commande']));
    $note_livraison = (int)$_POST['note-livraison'];
    $note_repas = (int)$_POST['note-repas'];
    $commentaire = htmlspecialchars(trim($_POST['commentaire']));
    $id_client = $_SESSION['id_utilisateur']; // On sait qui a posté l'avis grâce à la session

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
    } else {
        $tous_les_avis = [];
    }

    // Ajout du nouvel avis
    $tous_les_avis[] = $nouvel_avis;

    file_put_contents($fichier_avis, json_encode($tous_les_avis, JSON_PRETTY_PRINT));

    // Redirection du client vers son profil
    header("Location: ../profil.php?succes_avis=1");
    exit();
} else {
    // Si on essaie d'accéder à cette page sans envoyer le formulaire, on renvoie à l'accueil
    header("Location: ../accueil.php");
    exit();
}
