<?php
session_start();

if (!isset($_SESSION['utilisateur_connecte'])) {
    header("Location: connexion.php");
    exit();
}

if ($_SESSION['role'] != 'client') {
    header("Location: connexion.php");
    exit();
}

// Phase 3 : vérification du blocage
require_once('verif/check_session.php');

$plats = [];
if (file_exists('data/plats.json')) {
    $plats = json_decode(file_get_contents('data/plats.json'), true);
    if ($plats === null) {
        $plats = [];
    }
}

$catalogue = [];
foreach ($plats as $p) {
    $catalogue[$p['id_plat']] = $p;
}

$nb_articles_panier = 0;
if (isset($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $qte) {
        $nb_articles_panier += $qte;
    }
}

$min_date = date('Y-m-d\TH:i');
$limite = strtotime('+2 days');
$max_date = date('Y-m-d\TH:i', $limite);

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
    <title>Flagrant Délice - Mon Panier</title>
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
            <li><a href="accueil.php">ACCUEIL</a></li>
            <li><a href="presentation.php">LA CARTE</a></li>
            <li><a href="profil.php">MON COMPTE</a></li>
            <li><a href="panier.php" class="actif menu-panier-actif">🛒 PANIER (<?php echo $nb_articles_panier; ?>)</a></li>
            <li><a href="verif/deconnexion.php">DÉCONNEXION</a></li>
        </ul>
    </div>

    <div class="bandeau-titre">
        <h2><u>VOTRE PANIER</u></h2>
    </div>

    <?php
    if (isset($_GET['erreur'])) {
        if ($_GET['erreur'] == "date_invalide") {
            echo '<div class="message-alerte alerte-erreur">La date choisie est invalide. Veuillez choisir une date entre maintenant et dans 2 jours.</div>';
        } elseif ($_GET['erreur'] == "paiement_refuse") {
            echo '<div class="message-alerte alerte-erreur">Le paiement a été refusé. Veuillez réessayer.</div>';
        }
    }
    ?>

    <div class="conteneur-formulaire">
        <fieldset class="groupe-formulaire">
            <legend>RÉCAPITULATIF</legend>

            <?php if (empty($_SESSION['panier'])): ?>
                <p class="panier-vide-texte">Votre panier est cruellement vide.</p>
                <div class="form-actions">
                    <a href="presentation.php" class="btn-action btn-crime">COMMETTRE UN CRIME CULINAIRE</a>
                </div>
            <?php else: ?>
                <table class="tableau-commandes tableau-panier">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Prix Unitaire</th>
                            <th>Quantité</th>
                            <th>Sous-total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_commande = 0;
                        foreach ($_SESSION['panier'] as $id_plat => $quantite):
                            if (isset($catalogue[$id_plat])):
                                $plat = $catalogue[$id_plat];
                                $sous_total = $plat['prix'] * $quantite;
                                $total_commande += $sous_total;
                        ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($plat['nom']); ?></td>
                                    <td><?php echo number_format($plat['prix'], 2); ?> €</td>
                                    <td><strong><?php echo $quantite; ?></strong></td>
                                    <td><strong><?php echo number_format($sous_total, 2); ?> €</strong></td>
                                    <td class="colonne-actions">
                                        <button type="button" class="btn-action btn-qte-moins" onclick="modifierQuantitePanier('<?php echo $id_plat; ?>', -1)">−</button>
                                        <button type="button" class="btn-action btn-qte-plus" onclick="modifierQuantitePanier('<?php echo $id_plat; ?>', 1)">+</button>
                                        <button type="button" class="btn-action btn-supprimer-article" onclick="supprimerArticlePanier('<?php echo $id_plat; ?>')">✖</button>
                                    </td>
                                </tr>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </tbody>
                </table>

                <h3 class="total-panier">
                    TOTAL : <?php echo number_format($total_commande, 2); ?> €
                </h3>

                <?php
                require_once('verif/getapikey.php');
                $vendeur = "MEF-2_G";
                $transaction = substr(md5(uniqid(mt_rand(), true)), 0, 15);
                $montant = number_format($total_commande, 2, '.', '');
                $api_key = getAPIKey($vendeur);
                $url_retour = "http://localhost/FlagrantDelice/verif/validation_commande.php?mode=";
                $hash_control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $url_retour);
                ?>

                <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST" class="form-paiement" onsubmit="return validerPanier(event)">

                    <label class="label-paiement">Mode de consommation :</label><br>
                    <select name="mode_conso_choisi" id="mode_select" class="input-form input-moitie" onchange="updateRetour()">
                        <option value="livraison">Livraison à domicile</option>
                        <option value="emporter">À emporter</option>
                        <option value="sur_place">Sur place</option>
                    </select>

                    <br>
                    <label class="label-paiement">Moment de la préparation :</label><br>
                    <div class="bloc-radios-paiement">
                        <input type="radio" id="prep_immediate" name="type_preparation" value="immediate" checked onclick="document.getElementById('champ_date_heure').style.display='none'; updateRetour();">
                        <label for="prep_immediate" class="radio-margin">Préparation immédiate</label>

                        <input type="radio" id="prep_plustard" name="type_preparation" value="plustard" onclick="document.getElementById('champ_date_heure').style.display='block'; updateRetour();">
                        <label for="prep_plustard">Pour plus tard</label>
                    </div>

                    <div id="champ_date_heure" class="bloc-date-paiement">
                        <label for="date_commande" class="label-paiement">Date et heure souhaitées :</label><br>
                        <input type="datetime-local" id="date_commande" name="date_commande" class="input-form input-moitie" min="<?php echo $min_date; ?>" max="<?php echo $max_date; ?>" onchange="updateRetour()">
                        <br>
                        <span id="erreur-date-panier" class="message-erreur-js"></span>
                    </div>

                    <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
                    <input type="hidden" name="montant" value="<?php echo $montant; ?>">
                    <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
                    <input type="hidden" name="control" id="input_control" value="<?php echo $hash_control; ?>">
                    <input type="hidden" name="retour" id="input_retour" value="<?php echo $url_retour . 'livraison&type_preparation=immediate&date_commande='; ?>">

                    <br>
                    <button type="submit" class="btn-recherche btn-submit-form btn-payer">Payer avec CYBank et Valider</button>
                </form>
                <script>
                    function updateRetour() {
                        var mode = document.getElementById('mode_select').value;
                        var prep = document.querySelector('input[name="type_preparation"]:checked').value;
                        var date_cmd = document.getElementById('date_commande').value;

                        var base_url = "<?php echo $url_retour; ?>";
                        var new_retour = base_url + mode + "&type_preparation=" + prep + "&date_commande=" + encodeURIComponent(date_cmd);

                        document.getElementById('input_retour').value = new_retour;
                    }
                    window.addEventListener("load", updateRetour);
                </script>

            <?php endif; ?>
        </fieldset>
    </div>

    <div class="footer">
        <div class="footer-col">
            <p><strong>CONTACT</strong></p>
            <p>123 Rue du Crime Culinaire</p>
        </div>
        <div class="footer-col copyright-col">
            <p>©2026 Flagrant Délice</p>
        </div>
        <div class="footer-col">
            <p><strong>HORAIRES</strong></p>
            <p>Lun - Sam : 11h - 23h</p>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>
