<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Flagrant Délice - Administration</title>
    <link rel="icon" type="image/png" href="images/logopageweb.png">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

    <div class="header-top">
        <div class="logo-texte">FLAGRANT DÉLICE</div>
    </div>

    <div class="header-menu">
        <ul>
            <li><a href="accueil.php">RETOUR SITE</a></li>
            <li><a href="admin.php" class="actif">DASHBOARD ADMIN</a></li>
        </ul>
    </div>

    <div class="bandeau-titre">
        <h2><u>GESTION DES CLIENTS</u></h2>
    </div>

    <div class="conteneur-tableau">
        <table class="tableau-commandes">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Identité</th>
                    <th>Email</th>
                    <th>Ville</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#1</td>
                    <td><strong>Douille Tristan</strong></td>
                    <td>tristan@email.com</td>
                    <td>Cergy</td>
                    <td><a href="profil.php" class="btn-action">VOIR PROFIL</a></td>
                </tr>
                <tr>
                    <td>#2</td>
                    <td><strong>Ronaldo Cristiano</strong></td>
                    <td>cr7@email.com</td>
                    <td>Paris</td>
                    <td><a href="profil.php" class="btn-action">VOIR PROFIL</a></td>
                </tr>
                <tr>
                    <td>#3</td>
                    <td><strong>Dupont Jean</strong></td>
                    <td>jean@email.com</td>
                    <td>Pontoise</td>
                    <td><a href="profil.php" class="btn-action">VOIR PROFIL</a></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="bandeau-titre">
        <h2><u>ACCÈS RAPIDE (DEBUG)</u></h2>
    </div>

    <div class="admin-debug-container">

        <div class="admin-debug-col">
            <h3 class="admin-debug-title-client">CÔTÉ CLIENT</h3>
            <a href="accueil.php" class="btn-action btn-debug btn-debug-client">ACCUEIL</a>
            <a href="presentation.php" class="btn-action btn-debug btn-debug-client">LA CARTE</a>
            <a href="inscription.php" class="btn-action btn-debug btn-debug-client">INSCRIPTION</a>
            <a href="connexion.php" class="btn-action btn-debug btn-debug-client">CONNEXION</a>
            <a href="notation.php" class="btn-action btn-debug btn-debug-client">NOTATION</a>
        </div>

        <div class="admin-debug-col">
            <h3 class="admin-debug-title-staff">CÔTÉ STAFF</h3>
            <a href="commandes.php" class="btn-action btn-debug btn-debug-staff">CUISINE (Tablette)</a>
            <a href="livraison.php" class="btn-action btn-debug btn-debug-staff">LIVRAISON (Mobile)</a>
        </div>

    </div>

    <div class="footer">
        <div class="footer-col copyright-col">
            <p>©2026 Flagrant Délice - Administration</p>
        </div>
    </div>

</body>

</html>