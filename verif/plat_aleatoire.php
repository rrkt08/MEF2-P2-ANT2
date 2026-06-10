<?php
session_start();

// faut être client connecté
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['role'] != 'client') {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: ../presentation.php");
    exit();
}

$plats = json_decode(file_get_contents('../data/plats.json'), true);

if ($plats == null || count($plats) == 0) {
    header("Location: ../presentation.php");
    exit();
}

// on tire un index au hasard parmi tous les plats
$index = rand(0, count($plats) - 1);
$plat = $plats[$index];

// on l'ajoute dans le panier
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if (isset($_SESSION['panier'][$plat['id_plat']])) {
    $_SESSION['panier'][$plat['id_plat']] += 1;
} else {
    $_SESSION['panier'][$plat['id_plat']] = 1;
}

// on revient sur la carte avec un message
header("Location: ../presentation.php?ajout=ok&nom_plat=" . urlencode($plat['nom']));
exit();
?>
