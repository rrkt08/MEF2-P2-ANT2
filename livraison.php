<?php
session_start();

// Livreur ou admin ont l'accès
if (!isset($_SESSION['utilisateur_connecte'])) {
    header("Location: connexion.php");
    exit();
}

if ($_SESSION['role'] != "livreur") {
    if ($_SESSION['role'] != "admin") {
        header("Location: connexion.php");
        exit();
    }
}

$commandes = [];
if (file_exists('data/commandes.json')) {
    $commandes = json_decode(file_get_contents('data/commandes.json'), true);
}

$utilisateurs = [];
if (file_exists('data/utilisateurs.json')) {
    $utilisateurs = json_decode(file_get_contents('data/utilisateurs.json'), true);
}

// Recherche commande du livreur
$commande_a_livrer = null;

foreach ($commandes as $cmd) {
    if ($cmd['statut_preparation'] == "EN LIVRAISON") {
        // Si c'est le bon livreur, ou si c'est l'admin
        if ($cmd['id_livreur'] == $_SESSION['id_utilisateur'] || $_SESSION['role'] == 'admin') {
            $commande_a_livrer = $cmd;
            break;
        }
    }
}

// Recherche infos client
$client = null;
if ($commande_a_livrer != null) {
    foreach ($utilisateurs as $u) {
        if ($u['id_utilisateur'] == $commande_a_livrer['id_client']) {
            $client = $u;
            break;
        }
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
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

    <div class="header-top">
        <div class="logo-texte">FLAGRANT DÉLICE</div>
    </div>

    <div class="header-menu">
        <ul>
            <?php
            if ($_SESSION['role'] == 'admin') {
                echo '<li><a href="admin.php">RETOUR ADMIN</a></li>';
            }
            ?>
            <li><a href="verif/deconnexion.php">DÉCONNEXION</a></li>
        </ul>
    </div>

    <?php
    // Affichage commande trouvée
    if ($commande_a_livrer != null) {

        // Adresse GPS
        $adresse_complete = $commande_a_livrer['adresse_livraison']['rue'] . ', ' . $commande_a_livrer['adresse_livraison']['code_postal'] . ' ' . $commande_a_livrer['adresse_livraison']['ville'];
        $lien_gps = "https://www.google.com/maps/search/?api=1&query=" . urlencode($adresse_complete);

        echo '<div class="bandeau-titre">';
        echo '<h2><u>LIVRAISON #' . $commande_a_livrer['id_commande'] . '</u></h2>';
        echo '</div>';

        echo '<div class="bloc-livraison">';

        echo '<h3 class="titre-livraison">ADRESSE CLIENT</h3>';
        echo '<p class="info-livraison">';
        echo '<strong>' . $client['informations']['prenom'] . ' ' . $client['informations']['nom'] . '</strong><br>';
        echo $commande_a_livrer['adresse_livraison']['rue'] . '<br>';
        echo $commande_a_livrer['adresse_livraison']['code_postal'] . ' ' . $commande_a_livrer['adresse_livraison']['ville'];
        echo '</p>';

        // Affichage des compléments
        if ($commande_a_livrer['adresse_livraison']['complement'] != "") {
            echo '<h3 class="titre-livraison">COMPLÉMENTS</h3>';
            echo '<p class="info-livraison">';
            echo '<em>' . $commande_a_livrer['adresse_livraison']['complement'] . '</em>';
            echo '</p>';
        }

        echo '<div class="actions-livreur">';

        // Boutons
        echo '<a href="tel:' . $client['informations']['telephone'] . '" class="btn-livreur btn-tel">📞 APPELER CLIENT</a>';
        echo '<a href="' . $lien_gps . '" target="_blank" class="btn-livreur btn-gps">🗺️ OUVRIR GPS</a>';

        echo '<p class="titre-livraison">STATUT DE LA COMMANDE</p>';
        echo '<form action="#" method="get">';
        echo '<button type="button" class="btn-livreur btn-valider">✅ LIVRAISON TERMINÉE</button>';
        echo '<button type="button" class="btn-livreur" style="background-color: #333; color: white;">❌ ABANDONNÉE</button>';
        echo '</form>';

        echo '</div>';
        echo '</div>';
    } else {
        // Affichage pas de commande trouvée
        echo '<div class="bandeau-titre">';
        echo '<h2><u>AUCUNE LIVRAISON</u></h2>';
        echo '</div>';

        echo '<div class="bloc-livraison">';
        echo '<p class="info-livraison" style="margin-top: 50px;">Vous n\'avez aucune commande en cours de livraison pour le moment. Détendez-vous ! ☕</p>';
        echo '</div>';
    }
    ?>

    <div class="footer">
        <div class="footer-col copyright-col">
            <p><strong>ESPACE LIVREUR</strong></p>
            <p><?php echo $_SESSION['prenom']; ?></p>
        </div>
        <div class="footer-col copyright-col">
            <p>©2026 Flagrant Délice</p>
        </div>
    </div>

</body>

</html>