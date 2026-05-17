<?php
session_start();

// si pas admin => dégage
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['role'] != "admin") {
    header("Location: connexion.php");
    exit();
}

// au cas où un autre admin t'aurait bloqué
require_once('verif/check_session.php');

// charge les users
$fichier = 'data/utilisateurs.json';
if (file_exists($fichier)) {
    $utilisateurs = json_decode(file_get_contents($fichier), true);
} else {
    $utilisateurs = [];
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
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Flagrant Délice - Administration</title>
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
            <li><a href="accueil.php">RETOUR SITE</a></li>
            <li><a href="admin.php" class="actif">DASHBOARD ADMIN (<?php echo htmlspecialchars($_SESSION['prenom']); ?>)</a></li>
            <li><a href="verif/deconnexion.php">DÉCONNEXION</a></li>
        </ul>
    </div>

    <div class="bandeau-titre">
        <h2><u>GESTION DES UTILISATEURS</u></h2>
    </div>

    <!-- zone msg ajax (statut/remise/blocage) -->
    <div id="message-admin"></div>

    <div class="conteneur-tableau">
        <table class="tableau-commandes">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Rôle</th>
                    <th>Identité</th>
                    <th>Email</th>
                    <th>État</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($utilisateurs)) {
                    foreach ($utilisateurs as $user) {
                        echo '<tr>';
                        echo '<td>#' . $user['id_utilisateur'] . '</td>';

                        $role = strtoupper($user['role']);

                        // classe rouge pour les admins
                        $classe_role = "";
                        if ($role == 'ADMIN') {
                            $classe_role = "role-admin";
                        }
                        echo '<td class="' . $classe_role . '">' . $role . '</td>';

                        echo '<td><strong>' . htmlspecialchars($user['informations']['prenom'] . ' ' . $user['informations']['nom']) . '</strong></td>';
                        echo '<td>' . htmlspecialchars($user['login']) . '</td>';

                        // colonne état (bloqué / actif)
                        $est_bloque = (isset($user['bloque']) && $user['bloque'] == true);
                        if ($est_bloque) {
                            echo '<td class="statut-annule">BLOQUÉ</td>';
                        } else {
                            echo '<td class="statut-livre">ACTIF</td>';
                        }

                        echo '<td class="colonne-actions">';

                        echo '<button type="button" class="btn-admin btn-voir-profil" onclick="window.location.href=\'profil.php?id=' . $user['id_utilisateur'] . '\'">VOIR PROFIL</button>';

                        // statut + remise : que pour les users avec fidélité (= clients)
                        $a_fidelite = isset($user['fidelite']) && $user['fidelite'] !== null;

                        if ($a_fidelite) {
                            echo '<button type="button" class="btn-admin btn-statut" onclick="changerStatutFidelite(' . $user['id_utilisateur'] . ')">STATUT</button>';
                            echo '<button type="button" class="btn-admin btn-remise" onclick="accorderRemise(' . $user['id_utilisateur'] . ')">REMISE</button>';
                        } else {
                            echo '<span class="btn-admin admin-tiret">-</span>';
                            echo '<span class="btn-admin admin-tiret">-</span>';
                        }

                        // on bloque pas les admins, et on se bloque pas soi-même
                        if ($role != 'ADMIN') {
                            if ($est_bloque) {
                                echo '<button type="button" class="btn-admin btn-debloquer" data-action="debloquer" onclick="bloquerUtilisateurAjax(' . $user['id_utilisateur'] . ', this)">DÉBLOQUER</button>';
                            } else {
                                echo '<button type="button" class="btn-admin btn-bloquer" data-action="bloquer" onclick="bloquerUtilisateurAjax(' . $user['id_utilisateur'] . ', this)">BLOQUER</button>';
                            }
                        } else {
                            echo '<span class="btn-admin admin-tiret">-</span>';
                        }

                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="6">Aucun utilisateur trouvé.</td></tr>';
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
