<?php
session_start();

// utilisateur != admin
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['role'] != "admin") {
    header("Location: connexion.php");
    exit();
}

//Récupération des données
$fichier = 'data/utilisateurs.json';
if (file_exists($fichier)) {
    $utilisateurs = json_decode(file_get_contents($fichier), true);
} else {
    $utilisateurs = [];
}

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
    <title>Flagrant Délice - Administration</title>
    <link rel="icon" type="image/png" href="images/logopageweb.png">
    <link rel="stylesheet" type="text/css" id="theme-css" href="<?php echo $theme_choisi; ?>">
</head>

<body>

    <div class="header-top">
        <div class="logo-texte">FLAGRANT DÉLICE</div>
    </div>

    <div class="header-menu">
        <ul>
            <li><button type="button" class="btn-theme" onclick="changerTheme()">🌓</button></li>
            <li><a href="accueil.php">RETOUR SITE</a></li>
            <li><a href="admin.php" class="actif">DASHBOARD ADMIN (<?php echo $_SESSION['prenom']; ?>)</a></li>
            <li><a href="verif/deconnexion.php">DÉCONNEXION</a></li>
        </ul>
    </div>

    <div class="bandeau-titre">
        <h2><u>GESTION DES UTILISATEURS</u></h2>
    </div>

    <div class="conteneur-tableau">
        <table class="tableau-commandes">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Rôle</th>
                    <th>Identité</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Affichage du tableau avec tous les utilisateurs
                if (!empty($utilisateurs)) {
                    foreach ($utilisateurs as $user) {
                        echo '<tr>';
                        echo '<td>#' . $user['id_utilisateur'] . '</td>';

                        $role = strtoupper($user['role']);

                        //Si c'est un admin, on ajoute la classe "role-admin"
                        $classe_role = "";
                        if ($role == 'ADMIN') {
                            $classe_role = "role-admin";
                        }
                        echo '<td class="' . $classe_role . '">' . $role . '</td>';

                        echo '<td><strong>' . $user['informations']['prenom'] . ' ' . $user['informations']['nom'] . '</strong></td>';
                        echo '<td>' . $user['login'] . '</td>';

                        echo '<td class="colonne-actions">';

                        echo '<button type="button" class="btn-admin btn-voir-profil" onclick="window.location.href=\'profil.php?id=' . $user['id_utilisateur'] . '\'">VOIR PROFIL</button>';
                        echo '<button type="button" class="btn-admin btn-statut">STATUT</button>';
                        echo '<button type="button" class="btn-admin btn-remise">REMISE</button>';

                        // L'admin ne peut pas se bloquer (c'est logique)
                        if ($role != 'ADMIN') {
                            echo '<button type="button" class="btn-admin btn-bloquer">BLOQUER</button>';
                        } else {
                            echo '<span class="btn-admin admin-tiret">-</span>';
                        }

                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5">Aucun utilisateur trouvé.</td></tr>';
                }
                ?>
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
    <script src="script.js"></script>
</body>

</html>