<?php
session_start();

// Sécurisation : Seul le restaurateur et l'admin peuvent accéder à cette page
if (!isset($_SESSION['utilisateur_connecte']) || ($_SESSION['role'] != "restaurateur" && $_SESSION['role'] != "admin")) {
    header("Location: connexion.php");
    exit();
}

// Chargement des données JSON
$commandes = [];
if (file_exists('data/commandes.json')) {
    $commandes = json_decode(file_get_contents('data/commandes.json'), true);
}

$plats = [];
if (file_exists('data/plats.json')) {
    $donnees_plats = json_decode(file_get_contents('data/plats.json'), true);
    foreach ($donnees_plats as $p) {
        $plats[$p['id_plat']] = $p['nom'];
    }
}

$menus = [];
if (file_exists('data/menus.json')) {
    $donnees_menus = json_decode(file_get_contents('data/menus.json'), true);
    foreach ($donnees_menus as $m) {
        $menus[$m['id_menu']] = $m['nom'];
    }
}

$livreurs = [];
if (file_exists('data/utilisateurs.json')) {
    $donnees_users = json_decode(file_get_contents('data/utilisateurs.json'), true);
    foreach ($donnees_users as $u) {
        if ($u['role'] == 'livreur') {
            $livreurs[$u['id_utilisateur']] = $u['informations']['prenom'];
        }
    }
}

function getNomArticle($id, $type, $plats, $menus)
{
    if ($type == 'plat' && isset($plats[$id])) return $plats[$id];
    if ($type == 'menu' && isset($menus[$id])) return $menus[$id];
    return $id;
}

// Fonction pour générer un tableau identique à ton design
function afficherTableau($titre, $statut_cible, $commandes, $plats, $menus, $livreurs, $texte_bouton)
{
    echo '<h2 class="categorie-titre">' . $titre . '</h2>';
    echo '<div class="conteneur-tableau">';
    echo '<table class="tableau-commandes">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>N° Commande</th>';

    // Si c'est en livraison, on affiche le livreur au lieu de l'heure
    if ($statut_cible == "EN LIVRAISON") {
        echo '<th>Livreur</th>';
    } else {
        echo '<th>Heure</th>';
    }

    echo '<th>Détail du Menu</th>';
    echo '<th>Total</th>';
    echo '<th>Action</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    $trouve = false;
    foreach ($commandes as $cmd) {
        if ($cmd['statut_preparation'] == $statut_cible) {
            $trouve = true;
            echo '<tr>';
            echo '<td><strong>#' . htmlspecialchars($cmd['id_commande']) . '</strong></td>';

            if ($statut_cible == "EN LIVRAISON") {
                $nom_livreur = ($cmd['id_livreur'] !== null && isset($livreurs[$cmd['id_livreur']])) ? $livreurs[$cmd['id_livreur']] : "Non assigné";
                echo '<td>' . htmlspecialchars($nom_livreur) . '</td>';
            } else {
                echo '<td>' . date("H:i", strtotime($cmd['date_heure'])) . '</td>';
            }

            echo '<td>';
            foreach ($cmd['liste_articles'] as $article) {
                $nom_article = getNomArticle($article['id_article'], $article['type'], $plats, $menus);
                echo $article['quantite'] . 'x ' . htmlspecialchars($nom_article) . '<br>';
            }
            echo '</td>';

            echo '<td>' . number_format($cmd['prix_total'], 2) . ' €</td>';
            echo '<td><button class="btn-action btn-pret">' . $texte_bouton . '</button></td>';
            echo '</tr>';
        }
    }

    if (!$trouve) {
        echo '<tr><td colspan="5">Aucune commande dans cette catégorie.</td></tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Flagrant Délice - Commandes</title>
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
            <li><a href="commandes.php" class="actif">CUISINE</a></li>
            <li><a href="verif/deconnexion.php">DÉCONNEXION</a></li>
        </ul>
    </div>

    <div class="bandeau-titre">
        <h2><u>COMMANDES</u></h2>
    </div>

    <?php
    // Appel de la fonction pour générer les 4 tableaux demandés par la consigne
    afficherTableau("À PRÉPARER", "A PREPARER", $commandes, $plats, $menus, $livreurs, "PRÊT");
    afficherTableau("EN COURS", "EN COURS", $commandes, $plats, $menus, $livreurs, "TERMINER");
    afficherTableau("EN LIVRAISON", "EN LIVRAISON", $commandes, $plats, $menus, $livreurs, "DÉTAILS");
    afficherTableau("EN ATTENTE", "EN ATTENTE", $commandes, $plats, $menus, $livreurs, "VOIR");
    ?>

    <div class="footer">
        <div class="footer-col">
            <p><strong>ESPACE PRO</strong></p>
            <p>
                <?php
                if ($_SESSION['role'] == 'admin') {
                    echo 'Administrateur';
                } else {
                    echo 'Restaurateur';
                }
                ?> : <?php echo htmlspecialchars($_SESSION['prenom']); ?>
            </p>
        </div>
        <div class="footer-col copyright-col">
            <p>©2026 Flagrant Délice</p>
        </div>
    </div>

</body>

</html>