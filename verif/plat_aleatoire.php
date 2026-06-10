<?php
session_start();

// seul un client connecté peut utiliser cette fonctionnalité
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['role'] != 'client') {
    header("Location: ../connexion.php");
    exit();
}

// accès direct par l'url sans formulaire = on retourne sur la carte
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: ../presentation.php");
    exit();
}

$plats = json_decode(file_get_contents('../data/plats.json'), true);

if ($plats == null || count($plats) == 0) {
    header("Location: ../presentation.php");
    exit();
}

// rand() donne un index aléatoire entre 0 et le dernier index du tableau
$index = rand(0, count($plats) - 1);
$plat = $plats[$index];

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// si le plat est déjà dans le panier on ajoute 1, sinon on l'initialise à 1
if (isset($_SESSION['panier'][$plat['id_plat']])) {
    $_SESSION['panier'][$plat['id_plat']] += 1;
} else {
    $_SESSION['panier'][$plat['id_plat']] = 1;
}

// on revient sur la carte avec le nom du plat pour afficher un message
header("Location: ../presentation.php?ajout=ok&nom_plat=" . urlencode($plat['nom']));
exit();
?>
