<?php
session_start();
header("Content-Type: application/json");

// admin only
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['role'] != 'admin') {
    echo json_encode(["succes" => false, "message" => "Accès non autorisé."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["succes" => false, "message" => "Requête invalide."]);
    exit();
}

$id_user = $_POST['id_utilisateur'] ?? '';
$action = $_POST['action'] ?? '';

if ($action != "bloquer" && $action != "debloquer") {
    echo json_encode(["succes" => false, "message" => "Action inconnue."]);
    exit();
}

// on s'auto-bloque pas
if ($id_user == $_SESSION['id_utilisateur']) {
    echo json_encode(["succes" => false, "message" => "Vous ne pouvez pas vous bloquer vous-même."]);
    exit();
}

$fichier = '../data/utilisateurs.json';
$utilisateurs = json_decode(file_get_contents($fichier), true);

$trouve = false;
$message = "";

for ($i = 0; $i < count($utilisateurs); $i = $i + 1) {
    if ($utilisateurs[$i]['id_utilisateur'] == $id_user) {
        $trouve = true;

        // pas de blocage d'admin non plus
        if ($utilisateurs[$i]['role'] == "admin") {
            echo json_encode(["succes" => false, "message" => "On ne peut pas bloquer un administrateur."]);
            exit();
        }

        if ($action == "bloquer") {
            $utilisateurs[$i]['bloque'] = true;
            $message = "Utilisateur bloqué. Sa session sera terminée automatiquement.";
        } else {
            $utilisateurs[$i]['bloque'] = false;
            $message = "Utilisateur débloqué.";
        }
        break;
    }
}

if (!$trouve) {
    echo json_encode(["succes" => false, "message" => "Utilisateur introuvable."]);
    exit();
}

file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

echo json_encode(["succes" => true, "message" => $message]);
?>
