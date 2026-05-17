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

// Phase 3 : si l'utilisateur est connecté, on vérifie qu'il n'a pas été bloqué
require_once('verif/check_session.php');

$est_connecte = "0";
if (isset($_SESSION['utilisateur_connecte'])) {
    $est_connecte = "1";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Flagrant Délice - Accueil</title>
    <link rel="icon" type="image/png" href="images/logopageweb.png">
    <link rel="stylesheet" type="text/css" id="theme-css" href="<?php echo $theme_choisi; ?>">
</head>

<body data-connecte="<?php echo $est_connecte; ?>">

    <div class="header-top">
        <div class="logo-texte">FLAGRANT DÉLICE</div>
    </div>

    <div class="header-menu">
        <ul>
            <li><button type="button" class="btn-theme" onclick="changerTheme()">🌓</button></li>
            <li><a href="accueil.php" class="actif">ACCUEIL</a></li>
            <li><a href="presentation.php">LA CARTE</a></li>
            <?php if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['role'] == 'client'): ?>
                <li><a href="profil.php">MON COMPTE</a></li>
                <li><a href="verif/deconnexion.php">DÉCONNEXION</a></li>
            <?php else: ?>
                <li><a href="connexion.php">CONNEXION</a></li>
                <li><a href="inscription.php" class="btn-inscription">INSCRIPTION</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="banniere">
        <h1>MÉLANGES INTERDITS</h1>
    </div>

    <div class="zone-recherche">
        <form action="presentation.php" method="get" onsubmit="return validerRecherche(event)">
            <div class="conteneur-input-recherche">
                <input type="text" id="recherche-accueil" name="q" placeholder="RECHERCHER UN CRIME CULINAIRE..." class="input-recherche">
                <span id="erreur-recherche" class="message-erreur-js"></span>
            </div>
            <button type="submit" class="btn-recherche">RECHERCHER</button>
        </form>
    </div>

    <div class="bandeau-titre">
        <h2><u>PLATS POPULAIRES</u></h2>
    </div>

    <div class="plats-populaires">
        <div class="plat">
            <img src="images/Chiken_donut.png" alt="Chiken Donut Burger">
            <h3>CHIKEN DONUT BURGER</h3>
            <p class="prix">11.00 €</p>
            <a href="presentation.php" class="btn-plat">COMMANDER</a>
        </div>

        <div class="plat">
            <img src="images/Steak_donut.png" alt="Steak Donut Burger">
            <h3>STEAK DONUT BURGER</h3>
            <p class="prix">11.50 €</p>
            <a href="presentation.php" class="btn-plat">COMMANDER</a>
        </div>

        <div class="plat">
            <img src="images/Fish_donut.png" alt="Fish Donut Burger">
            <h3>FISH DONUT BURGER</h3>
            <p class="prix">10.00 €</p>
            <a href="presentation.php" class="btn-plat">COMMANDER</a>
        </div>

        <div class="plat">
            <img src="images/Frites_chocolat.png" alt="Frites Chocolat">
            <h3>FRITES CHOCOLAT</h3>
            <p class="prix">5.00 €</p>
            <a href="presentation.php" class="btn-plat">COMMANDER</a>
        </div>

        <div class="plat">
            <img src="images/pizza_ananas.png" alt="Pizza Hawaïenne">
            <h3>PIZZA HAWAÏENNE</h3>
            <p class="prix">10.00 €</p>
            <a href="presentation.php" class="btn-plat">COMMANDER</a>
        </div>

        <div class="plat">
            <img src="images/peche_mayo.png" alt="Peche Mayonnaise">
            <h3>PÊCHE MAYONNAISE</h3>
            <p class="prix">3.00 €</p>
            <a href="presentation.php" class="btn-plat">COMMANDER</a>
        </div>
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
