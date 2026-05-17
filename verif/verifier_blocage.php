<?php
session_start();
header("Content-Type: application/json");

// le js poll cette page toutes les 30s
// pour voir si l'admin nous a bloqué entre temps

if (!isset($_SESSION['utilisateur_connecte'])) {
    echo json_encode(["bloque" => false]);
    exit();
}

$id_user = $_SESSION['id_utilisateur'];

$fichier = '../data/utilisateurs.json';
if (!file_exists($fichier)) {
    echo json_encode(["bloque" => false]);
    exit();
}

$utilisateurs = json_decode(file_get_contents($fichier), true);

foreach ($utilisateurs as $u) {
    if ($u['id_utilisateur'] == $id_user) {
        if (isset($u['bloque']) && $u['bloque'] == true) {
            echo json_encode(["bloque" => true]);
            exit();
        }
        break;
    }
}

echo json_encode(["bloque" => false]);
?>
