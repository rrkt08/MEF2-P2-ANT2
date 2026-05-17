<?php
// Phase 3 : endpoint AJAX qui renvoie un fragment HTML
// avec les plats filtrés (catégorie / allergène / recherche).

session_start();

$categorie_filtre = $_POST['categorie'] ?? '';
$allergene_filtre = $_POST['allergene'] ?? '';
$recherche_filtre = strtolower(trim($_POST['recherche'] ?? ''));

$plats = [];
if (file_exists('../data/plats.json')) {
    $plats = json_decode(file_get_contents('../data/plats.json'), true);
    if ($plats === null) {
        $plats = [];
    }
}

// Liste des catégories possibles
$categories = [
    "donut-burgers" => "DONUT BURGERS",
    "pizzas"        => "PIZZAS",
    "varietes"      => "VARIÉTÉS",
    "desserts"      => "DESSERTS",
    "boissons"      => "BOISSONS"
];

// Si une catégorie est sélectionnée, on ne garde que celle-ci
if ($categorie_filtre != "") {
    $categories = [$categorie_filtre => strtoupper(str_replace("-", " ", $categorie_filtre))];
}

$plats_affiches_total = 0;

foreach ($categories as $id_cat => $nom_cat) {
    $plats_de_la_categorie = [];

    foreach ($plats as $plat) {
        if ($plat['categorie'] != $id_cat) {
            continue;
        }

        // Filtre allergène (on EXCLUT les plats qui contiennent l'allergène à éviter)
        if ($allergene_filtre != "") {
            if (in_array($allergene_filtre, $plat['informations']['allergenes'])) {
                continue;
            }
        }

        // Filtre recherche par nom
        if ($recherche_filtre != "") {
            if (strpos(strtolower($plat['nom']), $recherche_filtre) === false) {
                continue;
            }
        }

        $plats_de_la_categorie[] = $plat;
    }

    if (count($plats_de_la_categorie) > 0) {
        echo '<h2 class="categorie-titre">' . htmlspecialchars($nom_cat) . '</h2>';
        echo '<div class="plats-populaires">';

        foreach ($plats_de_la_categorie as $plat) {
            $nom_sans_extension = pathinfo($plat['image'], PATHINFO_FILENAME);

            if (file_exists("../images/" . $nom_sans_extension . ".png")) {
                $chemin_image = "images/" . $nom_sans_extension . ".png";
            } elseif (file_exists("../images/" . $nom_sans_extension . ".jpg")) {
                $chemin_image = "images/" . $nom_sans_extension . ".jpg";
            } else {
                $chemin_image = "images/fondplat.jpg";
            }

            echo '<div class="plat" data-prix="' . $plat['prix'] . '" data-nom="' . htmlspecialchars(strtolower($plat['nom'])) . '">';
            echo '<img src="' . $chemin_image . '" alt="' . htmlspecialchars($plat['nom']) . '">';
            echo '<h3>' . htmlspecialchars(strtoupper($plat['nom'])) . '</h3>';
            echo '<p class="description-plat">' . htmlspecialchars($plat['description']) . '</p>';
            echo '<p class="prix">' . number_format($plat['prix'], 2) . ' €</p>';

            // Si client connecté, on affiche le formulaire d'ajout au panier
            if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['role'] == 'client') {
                echo '<form action="verif/ajouter_panier.php" method="POST" onsubmit="return validerAjoutPanier(event)">';
                echo '<input type="hidden" name="id_plat" value="' . $plat['id_plat'] . '">';
                echo '<div class="bloc-qte">';
                echo '<label class="label-qte">Qté :</label>';
                echo '<input type="number" name="quantite" value="1" min="1" max="10" class="input-qte">';
                echo '<br><span class="message-erreur-js erreur-qte"></span>';
                echo '</div>';
                echo '<button type="submit" class="btn-ajouter">AJOUTER</button>';
                echo '</form>';
            }

            echo '</div>';
            $plats_affiches_total = $plats_affiches_total + 1;
        }

        echo '</div>';
    }
}

if ($plats_affiches_total == 0) {
    echo '<p class="panier-vide-texte">Aucun crime culinaire ne correspond à vos critères.</p>';
}
?>
