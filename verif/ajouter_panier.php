<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // On vérifie que l'utilisateur est bien un client connecté
    if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['role'] != 'client') {
        // S'il n'est pas connecté, on le force à s'identifier avant de commander
        header("Location: ../connexion.php");
        exit();
    }

    $id_plat = $_POST['id_plat'];
    $quantite = (int)$_POST['quantite'];

    // Si le panier n'existe pas encore dans la session, on le crée sous forme de tableau
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    // Si le plat est déjà dans le panier, on additionne la quantité, sinon on l'initialise
    if (isset($_SESSION['panier'][$id_plat])) {
        $_SESSION['panier'][$id_plat] += $quantite;
    } else {
        $_SESSION['panier'][$id_plat] = $quantite;
    }

    // On redirige vers la carte avec un paramètre de succès
    header("Location: ../presentation.php?ajout=ok");
    exit();
} else {
    // Redirection de sécurité si on accède à la page sans valider le formulaire
    header("Location: ../presentation.php");
    exit();
}
?>