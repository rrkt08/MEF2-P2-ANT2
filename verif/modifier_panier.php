<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['role'] != 'client') {
    echo json_encode(["succes" => false, "message" => "Vous devez être connecté en tant que client."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["succes" => false, "message" => "Requête invalide."]);
    exit();
}

$id_plat = $_POST['id_plat'] ?? '';
$delta = $_POST['delta'] ?? '';

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// l'article doit déjà être dans le panier pour pouvoir le modifier
if (!isset($_SESSION['panier'][$id_plat])) {
    echo json_encode(["succes" => false, "message" => "Article non présent dans le panier."]);
    exit();
}

// "supprimer" retire l'article directement sans passer par les quantités
if ($delta == "supprimer") {
    unset($_SESSION['panier'][$id_plat]);
    echo json_encode(["succes" => true, "message" => "Article retiré."]);
    exit();
}

$delta = (int)$delta;
$nouvelle_qte = $_SESSION['panier'][$id_plat] + $delta;

// si on descend à 0 ou moins, on supprime l'article du panier
if ($nouvelle_qte <= 0) {
    unset($_SESSION['panier'][$id_plat]);
    echo json_encode(["succes" => true, "message" => "Article retiré."]);
    exit();
}

// limite à 10 par article
if ($nouvelle_qte > 10) {
    echo json_encode(["succes" => false, "message" => "Quantité maximum 10 par article."]);
    exit();
}

$_SESSION['panier'][$id_plat] = $nouvelle_qte;
echo json_encode(["succes" => true, "message" => "Panier mis à jour.", "nouvelle_qte" => $nouvelle_qte]);
?>
