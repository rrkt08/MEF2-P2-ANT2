<?php
session_start();

// Redirection si non connecté
if (!isset($_SESSION['utilisateur_connecte'])) {
    header("Location: connexion.php");
    exit();
}

// On récupère l'ID de la commande depuis l'URL
$id_commande = isset($_GET['id_commande']) ? $_GET['id_commande'] : '';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Flagrant Délice - Votre Avis</title>
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
        </ul>
    </div>

    <div class="bandeau-titre">
        <h2><u>NOTER LA COMMANDE <?php echo htmlspecialchars($id_commande); ?></u></h2>
    </div>

    <div class="conteneur-formulaire">
        <form action="verif/verification_notation.php" method="post">
        <input type="hidden" name="id_commande" value="<?php echo htmlspecialchars($id_commande); ?>">
        
            <fieldset class="groupe-formulaire">
                <legend>LA LIVRAISON</legend>
                <label for="note-livraison">Note du livreur :</label><br>
                <select id="note-livraison" name="note-livraison" class="select-form">
                    <option value="5">★★★★★ - Parfait</option>
                    <option value="4">★★★★☆ - Très bien</option>
                    <option value="3">★★★☆☆ - Moyen</option>
                    <option value="2">★★☆☆☆ - Mauvais</option>
                    <option value="1">★☆☆☆☆ - Horrible</option>
                </select>
            </fieldset>

            <fieldset class="groupe-formulaire">
                <legend>LE REPAS</legend>
                <label for="note-repas">Qualité des plats :</label><br>
                <select id="note-repas" name="note-repas" class="select-form">
                    <option value="5">★★★★★ - Délicieux</option>
                    <option value="4">★★★★☆ - Bon</option>
                    <option value="3">★★★☆☆ - Moyen</option>
                    <option value="2">★★☆☆☆ - Pas bon</option>
                    <option value="1">★☆☆☆☆ - Immangeable</option>
                </select>

                <br><br>
                <label for="commentaire-avis">Un commentaire ?</label><br>
                <textarea id="commentaire-avis" name="commentaire" rows="5" placeholder="Dites-nous ce que vous avez pensé de ce mélange..." class="textarea-form"></textarea>
            </fieldset>

            <div class="form-actions">
                <button type="submit" class="btn-recherche">ENVOYER L'AVIS</button>
            </div>
        </form>
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

</body>

</html>