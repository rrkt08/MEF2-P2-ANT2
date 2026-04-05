<?php
session_start();

if (!isset($_SESSION['utilisateur_connecte'])) {
    header("Location: connexion.php");
    exit();
}

if ($_SESSION['role'] != "restaurateur") {
    if ($_SESSION['role'] != "admin") {
        header("Location: connexion.php");
        exit();
    }
}

if (!isset($_GET['id'])) {
    echo "Erreur : Aucun identifiant de commande fourni.";
    exit();
}
$id_commande = $_GET['id'];

$commandes = [];
if (file_exists('data/commandes.json')) {
    $commandes = json_decode(file_get_contents('data/commandes.json'), true);
}

$utilisateurs = [];
if (file_exists('data/utilisateurs.json')) {
    $utilisateurs = json_decode(file_get_contents('data/utilisateurs.json'), true);
}

$plats = [];
if (file_exists('data/plats.json')) {
    $plats = json_decode(file_get_contents('data/plats.json'), true);
}

$menus = [];
if (file_exists('data/menus.json')) {
    $menus = json_decode(file_get_contents('data/menus.json'), true);
}

// Recherche commande
$commande_actuelle = null;
foreach ($commandes as $cmd) {
    if ($cmd['id_commande'] == $id_commande) {
        $commande_actuelle = $cmd;
        break;
    }
}

// Recherche du client
$client = null;
foreach ($utilisateurs as $u) {
    if ($u['id_utilisateur'] == $commande_actuelle['id_client']) {
        $client = $u;
        break;
    }
}

$noms_articles = [];
foreach ($plats as $p) {
    $noms_articles[$p['id_plat']] = $p['nom'];
}
foreach ($menus as $m) {
    $noms_articles[$m['id_menu']] = $m['nom'];
}

// Recherche du livreur
$livreurs = [];
foreach ($utilisateurs as $u) {
    if ($u['role'] == 'livreur') {
        $livreurs[$u['id_utilisateur']] = $u['informations']['prenom'] . ' ' . $u['informations']['nom'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Détail <?php echo $id_commande; ?></title>
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
        <h2><u>DÉTAIL : <?php echo $id_commande; ?></u></h2>
    </div>

    <div class="conteneur-formulaire">

        <fieldset class="groupe-formulaire">
            <legend>INFORMATIONS CLIENT</legend>

            <p><strong>Nom :</strong> <?php echo $client['informations']['prenom'] . ' ' . $client['informations']['nom']; ?></p>
            <p><strong>Téléphone :</strong> <?php echo $client['informations']['telephone']; ?></p>
            <p><strong>Mode :</strong> <span class="texte-mode-conso"><?php echo $commande_actuelle['lieu_consommation']; ?></span></p>
            <?php
            if ($commande_actuelle['lieu_consommation'] == "livraison") {
                echo '<br><label>Adresse de livraison :</label>';
                echo '<div class="input-group-profil">';
                echo '<input type="text" class="input-form" value="' . $commande_actuelle['adresse_livraison']['rue'] . '" readonly>';
                echo '</div>';

                echo '<div class="input-group-profil">';
                echo '<input type="text" class="input-form" value="' . $commande_actuelle['adresse_livraison']['code_postal'] . ' ' . $commande_actuelle['adresse_livraison']['ville'] . '" readonly>';
                echo '</div>';

                if ($commande_actuelle['adresse_livraison']['complement'] != "") {
                    echo '<label>Infos supplémentaires :</label>';
                    echo '<div class="input-group-profil">';
                    echo '<input type="text" class="input-form" value="' . $commande_actuelle['adresse_livraison']['complement'] . '" readonly>';
                    echo '</div>';
                }
            }
            ?>
        </fieldset>

        <fieldset class="groupe-formulaire">
            <legend>CONTENU DE LA COMMANDE</legend>

            <table class="tableau-commandes">
                <thead>
                    <tr>
                        <th>Qté</th>
                        <th>Article</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($commande_actuelle['liste_articles'] as $article) {
                        echo '<tr>';
                        echo '<td><strong>' . $article['quantite'] . 'x</strong></td>';

                        $nom_affiche = $article['id_article'];
                        if (isset($noms_articles[$article['id_article']])) {
                            $nom_affiche = $noms_articles[$article['id_article']];
                        }
                        echo '<td>' . $nom_affiche . '</td>';

                        echo '<td>';
                        if (empty($article['options_choisies'])) {
                            echo '-';
                        } else {
                            echo implode(', ', $article['options_choisies']);
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

            <br>
            <br>
            <label>Statut du paiement :</label>
            <?php
            $classe_css_paiement = "texte-statut-attente";

            if ($commande_actuelle['statut_paiement'] == "paye") {
                $classe_css_paiement = "texte-statut-paye";
            }
            ?>
            <div class="input-group-profil">
                <input type="text" class="input-form <?php echo $classe_css_paiement; ?>" value="<?php echo $commande_actuelle['statut_paiement']; ?>" readonly>
            </div>


            <h3 class="categorie-titre premier-titre">TOTAL : <?php echo $commande_actuelle['prix_total']; ?> €</h3>
        </fieldset>

        <fieldset class="groupe-formulaire">
            <legend>MISE À JOUR</legend>

            <form action="#" method="get">
                <label for="nouveau_statut">État de la commande :</label><br>
                <select id="nouveau_statut" name="nouveau_statut" class="select-form">
                    <option value="EN ATTENTE" <?php if ($commande_actuelle['statut_preparation'] == 'EN ATTENTE') {
                                                    echo 'selected';
                                                } ?>>En attente</option>
                    <option value="A PREPARER" <?php if ($commande_actuelle['statut_preparation'] == 'A PREPARER') {
                                                    echo 'selected';
                                                } ?>>À préparer</option>
                    <option value="EN COURS" <?php if ($commande_actuelle['statut_preparation'] == 'EN COURS') {
                                                    echo 'selected';
                                                } ?>>En cours</option>
                    <option value="EN LIVRAISON" <?php if ($commande_actuelle['statut_preparation'] == 'EN LIVRAISON') {
                                                        echo 'selected';
                                                    } ?>>En livraison</option>
                </select>

                <br><br>

                <label for="id_livreur">Attribuer à un livreur :</label><br>
                <select id="id_livreur" name="id_livreur" class="select-form">
                    <option value="">-- Aucun livreur assigné --</option>
                    <?php
                    foreach ($livreurs as $id => $nom_livreur) {
                        $selected = "";
                        if ($commande_actuelle['id_livreur'] == $id) {
                            $selected = "selected";
                        }
                        echo '<option value="' . $id . '" ' . $selected . '>' . $nom_livreur . '</option>';
                    }
                    ?>
                </select>

                <br><br>
                <div class="form-actions">
                    <button type="button" class="btn-recherche btn-submit-form">SAUVEGARDER</button>
                </div>
            </form>
        </fieldset>

    </div>

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
                echo ' : ' . $_SESSION['prenom'];
                ?>
            </p>
        </div>
        <div class="footer-col copyright-col">
            <p>©2026 Flagrant Délice</p>
        </div>
        <div class="footer-col">
            <p><strong>DASHBOARD</strong></p>
            <p>Gestion des commandes</p>
        </div>
    </div>

</body>

</html>