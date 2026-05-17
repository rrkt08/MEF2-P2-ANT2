<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // faut être client pour ajouter au panier
    if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['role'] != 'client') {
        header("Location: ../connexion.php");
        exit();
    }

    $id_plat = $_POST['id_plat'];
    $quantite = (int)$_POST['quantite'];

    // init du panier si pas encore en session
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    // si le plat est déjà dans le panier on cumule, sinon on init
    if (isset($_SESSION['panier'][$id_plat])) {
        $_SESSION['panier'][$id_plat] += $quantite;
    } else {
        $_SESSION['panier'][$id_plat] = $quantite;
    }

    header("Location: ../presentation.php?ajout=ok");
    exit();
} else {
    // accès direct interdit
    header("Location: ../presentation.php");
    exit();
}
?>
