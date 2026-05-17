<?php
session_start();

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
    <title>Flagrant Délice - Connexion</title>
    <link rel="icon" type="image/png" href="images/logopageweb.png">
    <link rel="stylesheet" type="text/css" id="theme-css" href="<?php echo $theme_choisi; ?>">
</head>

<body data-connecte="0">

    <div class="header-top">
        <div class="logo-texte">FLAGRANT DÉLICE</div>
    </div>

    <div class="header-menu">
        <ul>
            <li><button type="button" class="btn-theme" onclick="changerTheme()">🌓</button></li>
            <li><a href="accueil.php">ACCUEIL</a></li>
            <li><a href="presentation.php">LA CARTE</a></li>
            <li><a href="connexion.php" class="actif">CONNEXION</a></li>
            <li><a href="inscription.php" class="btn-inscription">INSCRIPTION</a></li>
        </ul>
    </div>

    <div class="bandeau-titre">
        <h2><u>IDENTIFICATION</u></h2>
    </div>

    <?php
    // Messages d'erreurs ou de succès
    if (isset($_GET['succes']) && $_GET['succes'] == 1) {
        echo '<div class="message-alerte alerte-succes">Inscription réussie ! Vous pouvez maintenant vous connecter.</div>';
    }

    if (isset($_GET['erreur'])) {
        $message_erreur = "";
        if ($_GET['erreur'] == "identifiants") {
            $message_erreur = "E-mail ou mot de passe incorrect !";
        } elseif ($_GET['erreur'] == "vide") {
            $message_erreur = "Veuillez remplir tous les champs.";
        } elseif ($_GET['erreur'] == "bloque") {
            $message_erreur = "Votre compte a été bloqué par un administrateur. Contactez l'équipe pour plus d'informations.";
        }

        if ($message_erreur != "") {
            echo '<div class="message-alerte alerte-erreur">' . $message_erreur . '</div>';
        }
    }
    ?>

    <div class="conteneur-formulaire conteneur-connexion">
        <form action="verif/verification_connexion.php" method="post" onsubmit="return validerConnexion(event)">

            <fieldset class="groupe-formulaire">
                <legend>IDENTIFIANTS</legend>

                <label for="email-connexion">E-mail</label><br>
                <input type="email" id="email-connexion" name="email" class="input-form" placeholder="Votre adresse e-mail" maxlength="60" data-compteur="cpt-email-connexion" required>
                <span id="cpt-email-connexion" class="compteur-caracteres"></span>
                <span id="erreur-email-connexion" class="message-erreur-js"></span>
                <br><br>

                <label for="mdp-connexion">Mot de passe</label><br>
                <div class="conteneur-mdp-oeil">
                    <input type="password" id="mdp-connexion" name="mdp" class="input-form input-mdp-oeil" placeholder="Votre mot de passe" maxlength="40" data-compteur="cpt-mdp-connexion" required>
                    <span id="oeil-mdp-connexion" class="icone-oeil-form" onclick="afficherMasquerMdp('mdp-connexion', 'oeil-mdp-connexion')">👁️</span>
                </div>
                <span id="cpt-mdp-connexion" class="compteur-caracteres"></span>
                <span id="erreur-mdp-connexion" class="message-erreur-js"></span>
            </fieldset>

            <div class="form-actions">
                <button type="submit" class="btn-recherche btn-submit-form">SE CONNECTER</button>
                <br><br>
                <a href="inscription.php" class="lien-redirection">Pas encore de compte ? Créez-en un ici ></a>
            </div>

        </form>
    </div>

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
