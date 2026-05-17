<?php
session_start();

require_once('verif/check_session.php');

$plats = [];
if (file_exists('data/plats.json')) {
    $plats = json_decode(file_get_contents('data/plats.json'), true);
}

// compte le nb d'articles déjà au panier (pour le badge dans la nav)
$nb_articles_panier = 0;
if (isset($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $qte) {
        $nb_articles_panier += $qte;
    }
}

// si recherche depuis l'accueil, on pré-remplit
$recherche_initiale = "";
if (isset($_GET['q'])) {
    $recherche_initiale = htmlspecialchars(trim($_GET['q']));
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

$est_connecte = "0";
if (isset($_SESSION['utilisateur_connecte'])) {
    $est_connecte = "1";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Flagrant Délice - La Carte</title>
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
            <li><a href="accueil.php">ACCUEIL</a></li>
            <li><a href="presentation.php" class="actif">LA CARTE</a></li>
            <?php if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['role'] == 'client'): ?>
                <li><a href="profil.php">MON COMPTE</a></li>
                <li><a href="panier.php" class="menu-panier-actif">🛒 PANIER (<?php echo $nb_articles_panier; ?>)</a></li>
                <li><a href="verif/deconnexion.php">DÉCONNEXION</a></li>
            <?php else: ?>
                <li><a href="connexion.php">CONNEXION</a></li>
                <li><a href="inscription.php" class="btn-inscription">INSCRIPTION</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="bandeau-titre">
        <h2><u>TOUS NOS CRIMES CULINAIRES</u></h2>
    </div>

    <!-- filtres en ajax + tri en js sur les data deja affichées -->
    <div class="zone-filtres">
        <form action="#" method="get" onsubmit="return filtrerPlatsAjax(event)">
            <div class="conteneur-input-recherche">
                <input type="text" id="filtre-recherche" name="q" placeholder="Rechercher un crime..." class="input-recherche" value="<?php echo $recherche_initiale; ?>" maxlength="40" data-compteur="cpt-recherche-presentation">
                <span id="cpt-recherche-presentation" class="compteur-caracteres"></span>
            </div>

            <select name="categorie" id="filtre-categorie" class="menu-deroulant">
                <option value="">Toutes les catégories</option>
                <option value="donut-burgers">Donut Burgers</option>
                <option value="pizzas">Pizzas</option>
                <option value="varietes">Variétés</option>
                <option value="desserts">Desserts</option>
                <option value="boissons">Boissons</option>
            </select>

            <select name="allergene" id="filtre-allergene" class="menu-deroulant">
                <option value="">Tous les allergènes</option>
                <option value="gluten">Sans Gluten</option>
                <option value="lactose">Sans Lactose</option>
                <option value="oeuf">Sans Œuf</option>
                <option value="poisson">Sans Poisson</option>
                <option value="soja">Sans Soja</option>
            </select>

            <button type="submit" class="btn-recherche">FILTRER</button>
        </form>

        <br>

        <label class="label-tri">Trier par :</label>
        <select id="critere-tri" class="menu-deroulant" onchange="trierPlats(this.value)">
            <option value="">Aucun</option>
            <option value="prix-asc">Prix croissant</option>
            <option value="prix-desc">Prix décroissant</option>
            <option value="nom-asc">Nom (A → Z)</option>
        </select>
    </div>

    <?php
    if (isset($_GET['ajout']) && $_GET['ajout'] == 'ok') {
        echo '<div class="message-alerte alerte-succes">Article ajouté au panier avec succès !</div>';
    }
    ?>

    <!-- la zone qui se rafraichit en ajax quand on filtre -->
    <div id="zone-plats">
        <?php
        $categories = [
            "donut-burgers" => "DONUT BURGERS",
            "pizzas" => "PIZZAS",
            "varietes" => "VARIÉTÉS",
            "desserts" => "DESSERTS",
            "boissons" => "BOISSONS"
        ];

        $plats_affiches = 0;

        foreach ($categories as $id_cat => $nom_cat) :
            // filtre initial coté serveur (si on arrive avec q dans l'url)
            $plats_filtres = [];
            foreach ($plats as $plat) {
                if ($plat['categorie'] != $id_cat) continue;
                if ($recherche_initiale != "") {
                    if (stripos($plat['nom'], $recherche_initiale) === false) continue;
                }
                $plats_filtres[] = $plat;
            }

            if (count($plats_filtres) > 0) :
                $plats_affiches = $plats_affiches + count($plats_filtres);
        ?>
                <h2 class="categorie-titre"><?php echo $nom_cat; ?></h2>
                <div class="plats-populaires">
                    <?php foreach ($plats_filtres as $plat) : ?>
                        <?php
                        // ptit fix : png/jpg fallback
                        $nom_sans_extension = pathinfo($plat['image'], PATHINFO_FILENAME);

                        if (file_exists("images/" . $nom_sans_extension . ".png")) {
                            $chemin_image = "images/" . $nom_sans_extension . ".png";
                        } elseif (file_exists("images/" . $nom_sans_extension . ".jpg")) {
                            $chemin_image = "images/" . $nom_sans_extension . ".jpg";
                        } else {
                            $chemin_image = "images/fondplat.jpg";
                        }
                        ?>
                        <div class="plat" data-prix="<?php echo $plat['prix']; ?>" data-nom="<?php echo htmlspecialchars(strtolower($plat['nom'])); ?>">
                            <img src="<?php echo $chemin_image; ?>" alt="<?php echo htmlspecialchars($plat['nom']); ?>">
                            <h3><?php echo htmlspecialchars(strtoupper($plat['nom'])); ?></h3>
                            <p class="description-plat"><?php echo htmlspecialchars($plat['description']); ?></p>
                            <p class="prix"><?php echo number_format($plat['prix'], 2); ?> €</p>

                            <?php if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['role'] == 'client'): ?>
                                <form action="verif/ajouter_panier.php" method="POST" onsubmit="return validerAjoutPanier(event)">
                                    <input type="hidden" name="id_plat" value="<?php echo $plat['id_plat']; ?>">

                                    <div class="bloc-qte">
                                        <label class="label-qte">Qté :</label>
                                        <input type="number" name="quantite" value="1" min="1" max="10" class="input-qte">
                                        <br>
                                        <span class="message-erreur-js erreur-qte"></span>
                                    </div>

                                    <button type="submit" class="btn-ajouter">AJOUTER</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
        <?php
            endif;
        endforeach;

        if ($plats_affiches == 0) {
            echo '<p class="panier-vide-texte">Aucun crime culinaire ne correspond à votre recherche.</p>';
        }
        ?>
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
