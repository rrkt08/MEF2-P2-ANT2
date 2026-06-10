<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // seul un client connecté peut ajouter au panier
    if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['role'] != 'client') {
        header("Location: ../connexion.php");
        exit();
    }

    $id_plat = $_POST['id_plat'];
    $quantite = (int)$_POST['quantite'];

    // initialisation du panier s'il n'existe pas encore en session
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    // si le plat est déjà dans le panier on cumule les quantités
    if (isset($_SESSION['panier'][$id_plat])) {
        $_SESSION['panier'][$id_plat] += $quantite;
    } else {
        $_SESSION['panier'][$id_plat] = $quantite;
    }

    header("Location: ../presentation.php?ajout=ok");
    exit();

} else {
    // accès direct à ce script sans passer par un formulaire = on redirige
    header("Location: ../presentation.php");
    exit();
}
?>
