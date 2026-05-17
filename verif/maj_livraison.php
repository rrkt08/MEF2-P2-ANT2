<?php
session_start();
header("Content-Type: application/json");

// le livreur valide ou abandonne une livraison

if (!isset($_SESSION['utilisateur_connecte'])) {
    echo json_encode(["succes" => false, "message" => "Vous devez être connecté."]);
    exit();
}

if ($_SESSION['role'] != "livreur" && $_SESSION['role'] != "admin") {
    echo json_encode(["succes" => false, "message" => "Accès non autorisé."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["succes" => false, "message" => "Requête invalide."]);
    exit();
}

$id_commande = $_POST['id_commande'] ?? '';
$action = $_POST['action_livraison'] ?? '';

if ($action != "terminee" && $action != "abandonnee") {
    echo json_encode(["succes" => false, "message" => "Action inconnue."]);
    exit();
}

$fichier = '../data/commandes.json';
$commandes = json_decode(file_get_contents($fichier), true);

$trouvee = false;
for ($i = 0; $i < count($commandes); $i = $i + 1) {
    if ($commandes[$i]['id_commande'] == $id_commande) {
        $trouvee = true;

        // un livreur ne peut valider que sa propre cmd (l'admin oui)
        if ($_SESSION['role'] != 'admin' && $commandes[$i]['id_livreur'] != $_SESSION['id_utilisateur']) {
            echo json_encode(["succes" => false, "message" => "Cette livraison ne vous est pas assignée."]);
            exit();
        }

        if ($action == "terminee") {
            $commandes[$i]['statut_preparation'] = "LIVRÉ";
            $message = "Livraison terminée. Merci !";
        } else {
            $commandes[$i]['statut_preparation'] = "ANNULÉ";
            $message = "Livraison abandonnée.";
        }
        break;
    }
}

if (!$trouvee) {
    echo json_encode(["succes" => false, "message" => "Commande introuvable."]);
    exit();
}

file_put_contents($fichier, json_encode($commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

echo json_encode(["succes" => true, "message" => $message]);
?>
