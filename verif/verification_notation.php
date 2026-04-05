<?php
session_start();

// 1. On vérifie que l'utilisateur est bien connecté
if (!isset($_SESSION['utilisateur_connecte'])) {
    header("Location: ../connexion.php");
    exit();
}

// 2. On vérifie que le formulaire a bien été soumis via la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. On récupère et on nettoie les données envoyées par le formulaire
    $id_commande = htmlspecialchars(trim($_POST['id_commande']));
    $note_livraison = (int)$_POST['note-livraison'];
    $note_repas = (int)$_POST['note-repas'];
    $commentaire = htmlspecialchars(trim($_POST['commentaire']));
    $id_client = $_SESSION['id_utilisateur']; // On sait qui a posté l'avis grâce à la session

    // 4. On crée un tableau PHP avec le nouvel avis
    $nouvel_avis = [
        "id_commande" => $id_commande,
        "id_client" => $id_client,
        "note_livraison" => $note_livraison,
        "note_repas" => $note_repas,
        "commentaire" => $commentaire,
        "date_avis" => date("Y-m-d H:i:s")
    ];

    // 5. Chemin vers le fichier qui va stocker les avis
    $fichier_avis = '../data/avis.json';

    // 6. On lit le fichier existant (s'il existe), sinon on part d'un tableau vide
    if (file_exists($fichier_avis)) {
        $contenu = file_get_contents($fichier_avis);
        $tous_les_avis = json_decode($contenu, true);
    } else {
        $tous_les_avis = [];
    }

    // 7. On ajoute le nouvel avis à la liste
    $tous_les_avis[] = $nouvel_avis;

    // 8. On transforme le tableau PHP en texte JSON et on sauvegarde dans le fichier
    // JSON_PRETTY_PRINT permet d'avoir un fichier JSON bien indenté et lisible
    file_put_contents($fichier_avis, json_encode($tous_les_avis, JSON_PRETTY_PRINT));

    // 9. On redirige le client vers son profil avec un message de succès
    header("Location: ../profil.php?succes_avis=1");
    exit();

} else {
    // Si on essaie d'accéder à cette page sans envoyer le formulaire, on renvoie à l'accueil
    header("Location: ../accueil.php");
    exit();
}
?>