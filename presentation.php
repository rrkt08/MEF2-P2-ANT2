<?php
session_start();

// Récupération de tous les plats depuis le JSON
$plats = [];
if (file_exists('data/plats.json')) {
    $plats = json_decode(file_get_contents('data/plats.json'), true);
}

// Calcul du nombre d'articles actuellement dans le panier
$nb_articles_panier = 0;
if (isset($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $qte) {
        $nb_articles_panier += $qte;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Flagrant Délice - La Carte</title>
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
            <li><a href="presentation.php" class="actif">LA CARTE</a></li>
            <?php if(isset($_SESSION['utilisateur_connecte']) && $_SESSION['role'] == 'client'): ?>
                <li><a href="profil.php">MON COMPTE</a></li>
                <li><a href="panier.php" style="color: #ffcc00; font-weight: bold;">🛒 PANIER (<?php echo $nb_articles_panier; ?>)</a></li>
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

    <div class="zone-filtres">
        <form action="#" method="get">
            <input type="text" name="q" placeholder="Rechercher un crime..." class="input-recherche">

            <select name="categorie" class="menu-deroulant">
                <option value="">Toutes les catégories</option>
                <option value="donut-burgers">Donut Burgers</option>
                <option value="pizzas">Pizzas</option>
                <option value="varietes">Variétés</option>
                <option value="desserts">Desserts</option>
                <option value="boissons">Boissons</option>
            </select>

            <select name="allergene" class="menu-deroulant">
                <option value="">Tous les allergènes</option>
                <option value="gluten">Sans Gluten</option>
                <option value="lactose">Sans Lactose</option>
            </select>

            <button type="submit" class="btn-recherche">FILTRER</button>
        </form>
    </div>

    <?php
    // Gérer l'affichage d'un message de succès si un plat a été ajouté
    if (isset($_GET['ajout']) && $_GET['ajout'] == 'ok') {
        echo '<div style="background-color: #e6ffe6; color: #008000; text-align: center; padding: 10px; font-weight: bold; margin-bottom: 20px;">Article ajouté au panier avec succès !</div>';
    }
    ?>

    <?php
    // On définit les catégories que l'on veut afficher dans l'ordre
    $categories = [
        "donut-burgers" => "DONUT BURGERS",
        "pizzas" => "PIZZAS",
        "varietes" => "VARIÉTÉS",
        "desserts" => "DESSERTS",
        "boissons" => "BOISSONS"
    ];

    foreach ($categories as $id_cat => $nom_cat) : ?>
        <h2 class="categorie-titre"><?php echo $nom_cat; ?></h2>
        <div class="plats-populaires">
            <?php foreach ($plats as $plat) : ?>
                <?php if ($plat['categorie'] == $id_cat) : ?>
                    <div class="plat">
                        <?php
                    // Le code cherche le nom de l'image sans se soucier de l'extension (.jpg ou .png) écrite dans le JSON
                    $nom_sans_extension = pathinfo($plat['image'], PATHINFO_FILENAME);

                    if (file_exists("images/" . $nom_sans_extension . ".png")) {
                        $chemin_image = "images/" . $nom_sans_extension . ".png";
                    } elseif (file_exists("images/" . $nom_sans_extension . ".jpg")) {
                        $chemin_image = "images/" . $nom_sans_extension . ".jpg";
                    } else {
                        // Si vraiment l'image est introuvable, on met ton image de secours
                        $chemin_image = "images/fondplat.jpg"; 
                    }
                    ?>
                    <img src="<?php echo $chemin_image; ?>" alt="<?php echo htmlspecialchars($plat['nom']); ?>">
                        <h3><?php echo htmlspecialchars(strtoupper($plat['nom'])); ?></h3>
                        <p class="description-plat"><?php echo htmlspecialchars($plat['description']); ?></p>
                        <p class="prix"><?php echo number_format($plat['prix'], 2); ?> €</p>
                        
                        <form action="verif/ajouter_panier.php" method="POST">
                            <input type="hidden" name="id_plat" value="<?php echo $plat['id_plat']; ?>">
                            
                            <div style="margin-bottom: 12px;">
                                <label style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px; font-weight: bold;">Qté :</label>
                                <input type="number" name="quantite" value="1" min="1" max="10" style="width: 40px; padding: 5px; border-radius: 5px; border: none; text-align: center; font-weight: bold; margin-left: 5px; color: #000000; background-color: #ffffff;">
                            </div>
                            
                            <button type="submit" 
                                style="background-color: #ffffff; color: #e60012; padding: 10px 25px; border: none; border-radius: 25px; font-family: Impact, sans-serif; font-size: 22px; cursor: pointer; text-transform: uppercase; transition: 0.2s;"
                                onmouseover="this.style.backgroundColor='#e60012'; this.style.color='#ffffff';"
                                onmouseout="this.style.backgroundColor='#ffffff'; this.style.color='#e60012';">
                                AJOUTER
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

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