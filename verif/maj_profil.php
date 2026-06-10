<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION['utilisateur_connecte'])) {
    echo json_encode(["succes" => false, "message" => "Vous devez être connecté."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["succes" => false, "message" => "Requête invalide."]);
    exit();
}

// l'id vient toujours de la session, jamais du formulaire
// ça empêche de modifier le profil de quelqu'un d'autre
$id_user = $_SESSION['id_utilisateur'];
$champ = $_POST['champ'] ?? '';
$valeur = $_POST['valeur'] ?? '';

$valeur = htmlspecialchars(trim($valeur));

// on définit les champs qu'il est possible de modifier
$champs_simples = ["nom", "prenom", "telephone", "preferences_alimentaires"];
$champs_adresse = ["rue", "complement", "code_postal", "ville"];
$champ_email = "login";
$champ_pref_contact = "preferences_contact";

$fichier = '../data/utilisateurs.json';
$utilisateurs = json_decode(file_get_contents($fichier), true);

$trouve = false;
for ($i = 0; $i < count($utilisateurs); $i = $i + 1) {
    if ($utilisateurs[$i]['id_utilisateur'] == $id_user) {
        $trouve = true;

        if (in_array($champ, $champs_simples)) {
            // le téléphone a des contraintes de format
            if ($champ == "telephone") {
                $tel_propre = str_replace(' ', '', $valeur);
                if (strlen($tel_propre) != 10 || !ctype_digit($tel_propre) || $tel_propre[0] != '0') {
                    echo json_encode(["succes" => false, "message" => "Téléphone invalide (10 chiffres, commençant par 0)."]);
                    exit();
                }
                $valeur = $tel_propre;
            }
            // nom et prénom trop courts
            if (($champ == "nom" || $champ == "prenom") && strlen($valeur) < 2) {
                echo json_encode(["succes" => false, "message" => "Trop court (2 caractères minimum)."]);
                exit();
            }
            $utilisateurs[$i]['informations'][$champ] = $valeur;

        } elseif (in_array($champ, $champs_adresse)) {
            // code postal : exactement 5 chiffres
            if ($champ == "code_postal" && (strlen($valeur) != 5 || !ctype_digit($valeur))) {
                echo json_encode(["succes" => false, "message" => "Code postal invalide (5 chiffres)."]);
                exit();
            }
            $utilisateurs[$i]['informations']['adresse'][$champ] = $valeur;

        } elseif ($champ == $champ_email) {
            if (strpos($valeur, '@') === false || strpos($valeur, '.') === false) {
                echo json_encode(["succes" => false, "message" => "E-mail invalide."]);
                exit();
            }
            // vérification que l'email n'est pas déjà pris par un autre compte
            for ($j = 0; $j < count($utilisateurs); $j = $j + 1) {
                if ($j != $i && $utilisateurs[$j]['login'] == $valeur) {
                    echo json_encode(["succes" => false, "message" => "Cet e-mail est déjà utilisé."]);
                    exit();
                }
            }
            $utilisateurs[$i]['login'] = $valeur;

        } elseif ($champ == $champ_pref_contact) {
            // les préférences arrivent en "email,sms" et on les remet en tableau
            $liste = [];
            if ($valeur != "") {
                $liste = explode(",", $valeur);
            }
            $utilisateurs[$i]['informations']['preferences_contact'] = $liste;

        } else {
            // si le champ n'est pas dans la liste autorisée on refuse
            echo json_encode(["succes" => false, "message" => "Champ non autorisé."]);
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

echo json_encode(["succes" => true, "message" => "Information mise à jour."]);
?>
