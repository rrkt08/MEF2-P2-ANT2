<?php
session_start();
header("Content-Type: application/json");

// admin only
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['role'] != 'admin') {
    echo json_encode(["succes" => false, "message" => "Accès refusé."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["succes" => false, "message" => "Requête invalide."]);
    exit();
}

$id_user = $_POST['id_utilisateur'] ?? '';
$action = $_POST['action'] ?? '';
$valeur = $_POST['valeur'] ?? '';

$fichier = '../data/utilisateurs.json';
$utilisateurs = json_decode(file_get_contents($fichier), true);

// ordre des statuts fidélité (cycle dans l'ordre)
$ordre_statuts = ["SUSPECT CULINAIRE", "SERIAL CROQUEUR", "COMPLICE GOURMAND", "CRIMINEL CROQUANT"];

$trouve = false;
$message = "";
$nouveau_statut = "";
$nouveaux_points = 0;

for ($i = 0; $i < count($utilisateurs); $i = $i + 1) {
    if ($utilisateurs[$i]['id_utilisateur'] == $id_user) {
        $trouve = true;

        // que les clients ont une fidélité
        if (!isset($utilisateurs[$i]['fidelite']) || $utilisateurs[$i]['fidelite'] === null) {
            echo json_encode(["succes" => false, "message" => "Cet utilisateur n'a pas de fidélité."]);
            exit();
        }

        if ($action == "statut") {
            // passe au suivant dans la liste, et on boucle
            $statut_actuel = $utilisateurs[$i]['fidelite']['statut'];
            $idx = array_search($statut_actuel, $ordre_statuts);
            if ($idx === false) {
                $idx = -1;
            }
            $idx_nv = ($idx + 1) % count($ordre_statuts);
            $nouveau_statut = $ordre_statuts[$idx_nv];
            $utilisateurs[$i]['fidelite']['statut'] = $nouveau_statut;
            $message = "Statut changé en : " . $nouveau_statut;
        } elseif ($action == "remise") {
            // ajoute des points
            $pts = (int)$valeur;
            if ($pts < -500 || $pts > 500) {
                echo json_encode(["succes" => false, "message" => "Valeur de remise invalide (-500 à 500)."]);
                exit();
            }
            $utilisateurs[$i]['fidelite']['points'] = $utilisateurs[$i]['fidelite']['points'] + $pts;
            // pas de points négatifs
            if ($utilisateurs[$i]['fidelite']['points'] < 0) {
                $utilisateurs[$i]['fidelite']['points'] = 0;
            }
            $nouveaux_points = $utilisateurs[$i]['fidelite']['points'];
            $message = "Remise appliquée. Nouveau solde : " . $nouveaux_points . " points";
        } else {
            echo json_encode(["succes" => false, "message" => "Action inconnue."]);
            exit();
        }

        break;
    }
}

if (!$trouve) {
    echo json_encode(["succes" => false, "message" => "Utilisateur introuvable."]);
    exit();
}

file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

echo json_encode([
    "succes" => true,
    "message" => $message,
    "nouveau_statut" => $nouveau_statut,
    "nouveaux_points" => $nouveaux_points
]);
?>
