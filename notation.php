<?php
session_start();

// Redirection si non connecté
if (!isset($_SESSION['utilisateur_connecte'])) {
    header("Location: connexion.php");
    exit();
}

// Phase 3 : vérification du blocage
require_once('verif/check_session.php');

// On récupère l'ID de la commande depuis l'URL
$id_commande = isset($_GET['id_commande']) ? $_GET['id_commande'] : '';

// Phase 3 : vérification que la commande appartient au client, qu'elle est livrée
// et qu'elle n'a pas déjà été notée (pas de notation pour sur place / à emporter)
$commande_a_noter = null;
$erreur_acces = "";

if ($id_commande == "") {
    $erreur_acces = "Aucune commande spécifiée.";
} else {
    $commandes = json_decode(file_get_contents('data/commandes.json'), true);
    foreach ($commandes as $c) {
        if ($c['id_commande'] == $id_commande) {
            $commande_a_noter = $c;
            break;
        }
    }

    if ($commande_a_noter == null) {
        $erreur_acces = "Commande introuvable.";
    } elseif ($commande_a_noter['id_client'] != $_SESSION['id_utilisateur']) {
        $erreur_acces = "Cette commande ne vous appartient pas.";
    } elseif ($commande_a_noter['statut_preparation'] != "LIVRÉ") {
        $erreur_acces = "Cette commande n'a pas encore été livrée.";
    } elseif ($commande_a_noter['lieu_consommation'] != "livraison") {
        $erreur_acces = "Seules les commandes en livraison peuvent être notées.";
    } elseif (isset($commande_a_noter['deja_note']) && $commande_a_noter['deja_note'] == true) {
        $erreur_acces = "Vous avez déjà noté cette commande.";
    }
}

//Vérification du cookie pour dark/light mode
$theme_choisi = "style.css";
if (isset($_COOKIE['theme'])) {
    if ($_COOKIE['theme'] == 'sombre') {
        $theme_choisi = "style_sombre.css";
    } else if ($_COOKIE['theme'] == 'clair') {
        $theme_choisi = "style.css";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Flagrant Délice - Votre Avis</title>
    <link rel="icon" type="image/png" href="images/logopageweb.png">
    <link rel="stylesheet" type="text/css" id="theme-css" href="<?php echo $theme_choisi; ?>">
</head>

<body data-connecte="1">

    <div class="header-top">
        <div class="logo-texte">FLAGRANT DÉLICE</div>
    </div>

    <div class="header-menu">
        <ul>
            <li><button type="button" class="btn-theme" onclick="changerTheme()">🌓</button></li>
            <li><a href="accueil.php">ACCUEIL</a></li>
            <li><a href="presentation.php">LA CARTE</a></li>
            <li><a href="profil.php">MON COMPTE</a></li>
            <li><a href="verif/deconnexion.php">DÉCONNEXION</a></li>
        </ul>
    </div>

    <div class="bandeau-titre">
        <h2><u>NOTER LA COMMANDE <?php echo htmlspecialchars($id_commande); ?></u></h2>
    </div>

    <?php if ($erreur_acces != ""): ?>
        <div class="message-alerte alerte-erreur"><?php echo $erreur_acces; ?></div>
        <div class="form-actions">
            <a href="profil.php" class="btn-action btn-crime">Retour à mon profil</a>
        </div>
    <?php else: ?>

        <div class="conteneur-formulaire">
            <form action="verif/verification_notation.php" method="post" onsubmit="return validerNotation(event)">
                <input type="hidden" name="id_commande" value="<?php echo htmlspecialchars($id_commande); ?>">

                <fieldset class="groupe-formulaire">
                    <legend>LA LIVRAISON</legend>
                    <label for="note-livraison">Note du livreur :</label><br>
                    <select id="note-livraison" name="note-livraison" class="select-form">
                        <option value="5">★★★★★ - Parfait</option>
                        <option value="4">★★★★☆ - Très bien</option>
                        <option value="3">★★★☆☆ - Moyen</option>
                        <option value="2">★★☆☆☆ - Mauvais</option>
                        <option value="1">★☆☆☆☆ - Horrible</option>
                    </select>
                </fieldset>

                <fieldset class="groupe-formulaire">
                    <legend>LE REPAS</legend>
                    <label for="note-repas">Qualité des plats :</label><br>
                    <select id="note-repas" name="note-repas" class="select-form">
                        <option value="5">★★★★★ - Délicieux</option>
                        <option value="4">★★★★☆ - Bon</option>
                        <option value="3">★★★☆☆ - Moyen</option>
                        <option value="2">★★☆☆☆ - Pas bon</option>
                        <option value="1">★☆☆☆☆ - Immangeable</option>
                    </select>

                    <br><br>
                    <label for="commentaire-avis">Un commentaire ?</label><br>
                    <textarea id="commentaire-avis" name="commentaire" rows="5" placeholder="Dites-nous ce que vous avez pensé de ce mélange..." class="textarea-form" maxlength="250" data-compteur="cpt-commentaire"></textarea>
                    <span id="cpt-commentaire" class="compteur-caracteres"></span>
                    <span id="erreur-commentaire" class="message-erreur-js"></span>
                </fieldset>

                <div class="form-actions">
                    <button type="submit" class="btn-recherche">ENVOYER L'AVIS</button>
                </div>
            </form>
        </div>

    <?php endif; ?>

    <div class="footer">
        <div class="footer-col">
            <p><strong>CONTACT</strong></p>
            <p>123 Rue du Crime Culinaire</p>
            <p>01 23 45 67 89</p>
        </div>

        <div class="footer-col copyright-col">
            <p>©2026 Flagrant Délice</p>
        </div>

        <div class="footer-col">
            <p><strong>HORAIRES</strong></p>
            <p>Lun - Sam : 11h - 23h</p>
            <p>Dimanche : 12h - 22h</p>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>
