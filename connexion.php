<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Flagrant Délice - Connexion</title>
    <link rel="icon" type="image/png" href="images/logopageweb.png">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

    <div class="header-top">
        <div class="logo-texte">FLAGRANT DÉLICE</div>
    </div>

    <div class="header-menu">
        <ul>
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
        echo '<div style="background-color: #e6ffe6; color: #008000; text-align: center; padding: 15px; font-family: Impact, sans-serif; font-size: 20px; letter-spacing: 1px;">Inscription réussie ! Vous pouvez maintenant vous connecter.</div>';
    }

    if (isset($_GET['erreur'])) {
        $message_erreur = "";
        if ($_GET['erreur'] == "identifiants") {
            $message_erreur = "E-mail ou mot de passe incorrect !";
        } elseif ($_GET['erreur'] == "vide") {
            $message_erreur = "Veuillez remplir tous les champs.";
        }

        if ($message_erreur != "") {
            echo '<div style="background-color: #ffe6e6; color: #e60012; text-align: center; padding: 15px; font-family: Impact, sans-serif; font-size: 20px; letter-spacing: 1px;">' . $message_erreur . '</div>';
        }
    }
    ?>

    <div class="conteneur-formulaire conteneur-connexion">
        <form action="verif/verification_connexion.php" method="post">

            <fieldset class="groupe-formulaire">
                <legend>IDENTIFIANTS</legend>

                <label for="email-connexion">E-mail</label><br>
                <input type="email" id="email-connexion" name="email" class="input-form" placeholder="Votre adresse e-mail" required>
                <br><br>

                <label for="mdp-connexion">Mot de passe</label><br>
                <input type="password" id="mdp-connexion" name="mdp" class="input-form" placeholder="Votre mot de passe" required>
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

</body>

</html>