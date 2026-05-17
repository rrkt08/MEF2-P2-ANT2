<?php
session_start();
header("Content-Type: application/json");

// changement de statut d'une cmd (resto / admin)

if (!isset($_SESSION['utilisateur_connecte'])) {
    echo json_encode(["succes" => false, "message" => "Vous devez être connecté."]);
    exit();
}

if ($_SESSION['role'] != "restaurateur" && $_SESSION['role'] != "admin") {
    echo json_encode(["succes" => false, "message" => "Accès non autorisé."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["succes" => false, "message" => "Requête invalide."]);
    exit();
}

$id_commande = $_POST['id_commande'] ?? '';
$nouveau_statut = $_POST['nouveau_statut'] ?? '';
$id_livreur = $_POST['id_livreur'] ?? '';

// les statuts autorisés
$statuts_ok = ["EN ATTENTE", "A PREPARER", "EN COURS", "EN LIVRAISON", "LIVRÉ"];
if (!in_array($nouveau_statut, $statuts_ok)) {
    echo json_encode(["succes" => false, "message" => "Statut non valide."]);
    exit();
}

$fichier = '../data/commandes.json';
$commandes = json_decode(file_get_contents($fichier), true);

$trouvee = false;
for ($i = 0; $i < count($commandes); $i = $i + 1) {
    if ($commandes[$i]['id_commande'] == $id_commande) {
        $trouvee = true;

        // si on passe en livraison, faut un livreur
        if ($nouveau_statut == "EN LIVRAISON" && $id_livreur == "") {
            echo json_encode(["succes" => false, "message" => "Un livreur doit être assigné."]);
            exit();
        }

        $commandes[$i]['statut_preparation'] = $nouveau_statut;

        if ($id_livreur != "") {
            $commandes[$i]['id_livreur'] = (int)$id_livreur;
        }

        break;
    }
}

if (!$trouvee) {
    echo json_encode(["succes" => false, "message" => "Commande introuvable."]);
    exit();
}

file_put_contents($fichier, json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

echo json_encode(["succes" => true, "message" => "Statut mis à jour."]);
?>
