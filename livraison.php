<?php
session_start();

// livreur ou admin
if (!isset($_SESSION['utilisateur_connecte'])) {
    header("Location: connexion.php");
    exit();
}

if ($_SESSION['role'] != "livreur" && $_SESSION['role'] != "admin") {
    header("Location: connexion.php");
    exit();
}

require_once('verif/check_session.php');

$commandes = [];
if (file_exists('data/commandes.json')) {
    $commandes = json_decode(file_get_contents('data/commandes.json'), true);
}

$utilisateurs = [];
if (file_exists('data/utilisateurs.json')) {
    $utilisateurs = json_decode(file_get_contents('data/utilisateurs.json'), true);
}

// on cherche la cmd assignée à ce livreur (ou la 1ère en livraison si admin)
$commande_a_livrer = null;

foreach ($commandes as $cmd) {
    if ($cmd['statut_preparation'] == "EN LIVRAISON") {
        if ($cmd['id_livreur'] == $_SESSION['id_utilisateur'] || $_SESSION['role'] == 'admin') {
            $commande_a_livrer = $cmd;
            break;
        }
    }
}

// infos du client si on a une cmd
$client = null;
if ($commande_a_livrer != null) {
    foreach ($utilisateurs as $u) {
        if ($u['id_utilisateur'] == $commande_a_livrer['id_client']) {
            $client = $u;
            break;
        }
    }
}

// theme
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flagrant Délice - Espace Livreur</title>
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
            <?php
            if ($_SESSION['role'] == 'admin') {
                echo '<li><a href="admin.php">RETOUR ADMIN</a></li>';
            }
            ?>
            <li><a href="verif/deconnexion.php">DÉCONNEXION</a></li>
        </ul>
    </div>

    <?php
    if ($commande_a_livrer != null) {

        // construit l'url google maps avec l'adresse
        $adresse_complete = $commande_a_livrer['adresse_livraison']['rue'] . ', ' . $commande_a_livrer['adresse_livraison']['code_postal'] . ' ' . $commande_a_livrer['adresse_livraison']['ville'];
        $lien_gps = "https://www.google.com/maps/search/?api=1&query=" . urlencode($adresse_complete);

        echo '<div class="bandeau-titre">';
        echo '<h2><u>LIVRAISON #' . htmlspecialchars($commande_a_livrer['id_commande']) . '</u></h2>';
        echo '</div>';

        echo '<div id="message-livraison"></div>';

        echo '<div class="bloc-livraison">';

        echo '<h3 class="titre-livraison">ADRESSE CLIENT</h3>';
        echo '<p class="info-livraison">';
        echo '<strong>' . htmlspecialchars($client['informations']['prenom'] . ' ' . $client['informations']['nom']) . '</strong><br>';
        echo htmlspecialchars($commande_a_livrer['adresse_livraison']['rue']) . '<br>';
        echo htmlspecialchars($commande_a_livrer['adresse_livraison']['code_postal'] . ' ' . $commande_a_livrer['adresse_livraison']['ville']);
        echo '</p>';

        // compléments si y en a (digicode, étage...)
        if ($commande_a_livrer['adresse_livraison']['complement'] != "") {
            echo '<h3 class="titre-livraison">COMPLÉMENTS</h3>';
            echo '<p class="info-livraison">';
            echo '<em>' . htmlspecialchars($commande_a_livrer['adresse_livraison']['complement']) . '</em>';
            echo '</p>';
        }

        echo '<div class="actions-livreur" id="actions-livreur-bloc">';

        echo '<input type="hidden" id="id-cmd-livraison" value="' . htmlspecialchars($commande_a_livrer['id_commande']) . '">';

        echo '<a href="tel:' . htmlspecialchars($client['informations']['telephone']) . '" class="btn-livreur btn-tel">📞 APPELER CLIENT</a>';
        echo '<a href="' . htmlspecialchars($lien_gps) . '" target="_blank" class="btn-livreur btn-gps">🗺️ OUVRIR GPS</a>';

        echo '<p class="titre-livraison">STATUT DE LA COMMANDE</p>';
        // boutons ajax
        echo '<button type="button" class="btn-livreur btn-valider" onclick="confirmerLivraison(\'terminee\')">✅ LIVRAISON TERMINÉE</button>';
        echo '<button type="button" class="btn-livreur btn-abandon" onclick="confirmerLivraison(\'abandonnee\')">❌ ABANDONNÉE</button>';

        echo '</div>';
        echo '</div>';
    } else {
        // pas de cmd en livraison pour ce livreur
        echo '<div class="bandeau-titre">';
        echo '<h2><u>AUCUNE LIVRAISON</u></h2>';
        echo '</div>';

        echo '<div class="bloc-livraison">';
        echo '<p class="info-livraison texte-repos">Vous n\'avez aucune commande à livrer pour le moment. Détendez-vous !</p>';
        echo '</div>';
    }
    ?>

    <div class="footer">
        <div class="footer-col copyright-col">
            <p><strong>ESPACE LIVREUR</strong></p>
            <p><?php echo htmlspecialchars($_SESSION['prenom']); ?></p>
        </div>
        <div class="footer-col copyright-col">
            <p>©2026 Flagrant Délice</p>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>
