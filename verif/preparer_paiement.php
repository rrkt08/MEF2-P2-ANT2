<?php
session_start();
require_once('getapikey.php');

// si pas client, on dégage
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['role'] != 'client') {
    header("Location: ../connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: ../panier.php");
    exit();
}

// On stocke les choix du client en session avant d'aller à la banque
// (sinon le hash et l'url ne matchent pas)
$_SESSION['paiement_mode'] = $_POST['mode_conso_choisi'] ?? 'livraison';
$_SESSION['paiement_prep'] = $_POST['type_preparation'] ?? 'immediate';
$_SESSION['paiement_date'] = $_POST['date_commande'] ?? '';

// si on était en train de modifier une commande payée (cas phase 3)
$id_cmd_modif = $_POST['cmd_modif'] ?? '';

$vendeur = "MEF-2_G";
$transaction = substr(md5(uniqid(mt_rand(), true)), 0, 15);
$montant = number_format((float)$_POST['montant'], 2, '.', '');
$api_key = getAPIKey($vendeur);

// url de retour fixe pcq on a déjà tout mis en session
$url_retour = "http://localhost/FlagrantDelice/verif/validation_commande.php";
if ($id_cmd_modif != "") {
    $url_retour = $url_retour . "?cmd_modif=" . urlencode($id_cmd_modif);
}

// le # final est obligatoire dans le hash selon le doc cybank
$hash_control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $url_retour . "#");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Redirection paiement...</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
</head>

<body onload="document.forms[0].submit()">

    <div class="bandeau-titre">
        <h2><u>REDIRECTION...</u></h2>
    </div>

    <p style="text-align:center; font-family:Arial; margin-top:30px;">
        Connexion à la plateforme CYBank en cours...<br>
        Si la redirection ne se fait pas automatiquement, cliquez sur le bouton.
    </p>

    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST" class="form-paiement">
        <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
        <input type="hidden" name="montant" value="<?php echo $montant; ?>">
        <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
        <input type="hidden" name="control" value="<?php echo $hash_control; ?>">
        <input type="hidden" name="retour" value="<?php echo $url_retour; ?>">
        <button type="submit" class="btn-recherche btn-payer">Continuer vers le paiement</button>
    </form>

</body>

</html>
