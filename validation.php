<?php
session_start();

if (!isset($_SESSION['utilisateur_connecte'])) {
    header("Location: connexion.php");
    exit();
}

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [
        [
            "type" => "plat",
            "id_article" => "CHIKEN_DONUT",
            "quantite" => 1,
            "options_choisies" => []
        ],
        [
            "type" => "plat",
            "id_article" => "SODA_CORNICHON",
            "quantite" => 1,
            "options_choisies" => []
        ]
    ];
    $_SESSION['total_panier'] = 13.00;
}

$min_date = date('Y-m-d\TH:i');
$max_date = date('Y-m-d\TH:i', strtotime('+2 days'));
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Flagrant Délice - Validation</title>
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
            <li><a href="profil.php">MON COMPTE</a></li>
            <li><a href="verif/deconnexion.php">DÉCONNEXION</a></li>
        </ul>
    </div>

    <div class="bandeau-titre">
        <h2><u>VALIDATION DE LA COMMANDE</u></h2>
    </div>

    <?php
    if (isset($_GET['erreur'])) {
        if ($_GET['erreur'] == "date_invalide") {
            echo '<div style="background-color: #ffe6e6; color: #e60012; text-align: center; padding: 15px; font-family: Impact, sans-serif; font-size: 20px; letter-spacing: 1px;">La date choisie est invalide. Veuillez choisir une date entre maintenant et dans 2 jours.</div>';
        }
    }
    ?>

    <div class="conteneur-formulaire">
        <form action="verif/verification_commande.php" method="post">

            <fieldset class="groupe-formulaire">
                <legend>VOTRE PANIER (TEST)</legend>
                <p style="color: #00a8e8; font-weight: bold; font-size: 18px;">Montant total : <?php echo $_SESSION['total_panier']; ?> €</p>
            </fieldset>

            <fieldset class="groupe-formulaire">
                <legend>LIEU DE CONSOMMATION</legend>
                <select name="lieu_consommation" class="select-form" required>
                    <option value="sur place">Sur place</option>
                    <option value="a emporter">À emporter</option>
                    <option value="livraison">En livraison</option>
                </select>
            </fieldset>

            <fieldset class="groupe-formulaire">
                <legend>MOMENT DE LA PRÉPARATION</legend>

                <div class="input-group-profil">
                    <input type="radio" id="prep_immediate" name="type_preparation" value="immediate" checked onclick="document.getElementById('champ_date_heure').style.display='none'">
                    <label for="prep_immediate" class="label-checkbox marge-droite">Préparation immédiate</label>

                    <input type="radio" id="prep_plustard" name="type_preparation" value="plustard" onclick="document.getElementById('champ_date_heure').style.display='block'">
                    <label for="prep_plustard" class="label-checkbox">Pour plus tard</label>
                </div>

                <div id="champ_date_heure" style="display: none; margin-top: 15px;">
                    <label for="date_commande">Date et heure souhaitées :</label><br>
                    <input type="datetime-local" id="date_commande" name="date_commande" class="input-form" min="<?php echo $min_date; ?>" max="<?php echo $max_date; ?>">
                </div>
            </fieldset>

            <div class="form-actions">
                <button type="submit" class="btn-recherche btn-submit-form">CONFIRMER LA COMMANDE</button>
            </div>

        </form>
    </div>

    <div class="footer">
        <div class="footer-col copyright-col">
            <p>©2026 Flagrant Délice</p>
        </div>
    </div>

</body>

</html>