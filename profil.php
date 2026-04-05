<?php
session_start();

// Vérification de la connexion
if (!isset($_SESSION['utilisateur_connecte'])) {
    header("Location: connexion.php");
    exit();
}

// Quel profil on va afficher
if ($_SESSION['role'] == "admin") {
    // Si l'admin regarde un profil via le bouton "VOIR PROFIL" de la page admin
    if (isset($_GET['id'])) {
        $id_profil_a_afficher = $_GET['id'];
    } else {
        // Si l'admin clique sur "MON COMPTE" dans la barre de navigation
        $id_profil_a_afficher = $_SESSION['id_utilisateur'];
    }
} else if ($_SESSION['role'] == "client") {
    // Si c'est un client, on affiche son profil
    $id_profil_a_afficher = $_SESSION['id_utilisateur'];
} else {
    header("Location: connexion.php");
    exit();
}

// Chargement des données
$utilisateurs = json_decode(file_get_contents('data/utilisateurs.json'), true);
if ($utilisateurs === null) {
    $utilisateurs = [];
}

$commandes = json_decode(file_get_contents('data/commandes.json'), true);
if ($commandes === null) {
    $commandes = [];
}

// Recherche info de l'utilisateur concerné
$user_data = null;
foreach ($utilisateurs as $u) {
    if ($u['id_utilisateur'] == $id_profil_a_afficher) {
        $user_data = $u;
        break;
    }
}

if (!$user_data) {
    die("Utilisateur introuvable dans la base de données.");
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Flagrant Délice - Mon Profil</title>
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
            <li><a href="profil.php" class="actif">MON COMPTE</a></li>
            <?php
            // Si c'est l'admin il peut retourner a son tableau de bord
            if ($_SESSION['role'] == 'admin'):
            ?>
                <li><a href="admin.php">RETOUR ADMIN</a></li>
            <?php endif; ?>
            <li><a href="verif/deconnexion.php">DÉCONNEXION</a></li>
        </ul>
    </div>

    <div class="bandeau-titre">
        <h2><u>MON ESPACE PERSONNEL</u></h2>
    </div>

    <div class="conteneur-formulaire">

        <?php
        // Le bloc fidélité ne s'affiche que si l'utilisateur a des points de fidélité (l'admin, restaurateur, livreur n'en ont pas par exemple)
        if (isset($user_data['fidelite']) && $user_data['fidelite'] !== null):
        ?>
            <fieldset class="groupe-formulaire fidelite-box">
                <legend>MA FIDÉLITÉ</legend>
                <div class="fidelite-content">
                    <h3 class="fidelite-titre">STATUT : <?php echo htmlspecialchars($user_data['fidelite']['statut']); ?></h3>
                    <p class="fidelite-points">Points cumulés : <span class="fidelite-valeur"><?php echo htmlspecialchars($user_data['fidelite']['points']); ?></span></p>
                </div>
            </fieldset>
        <?php endif; ?>

        <fieldset class="groupe-formulaire">
            <legend>INFORMATIONS DU COMPTE</legend>

            <label>Nom :</label><br>
            <div class="input-group-profil">
                <input type="text" value="<?php echo htmlspecialchars($user_data['informations']['nom']); ?>" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Prénom :</label><br>
            <div class="input-group-profil">
                <input type="text" value="<?php echo htmlspecialchars($user_data['informations']['prenom']); ?>" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Adresse E-mail :</label><br>
            <div class="input-group-profil">
                <input type="email" value="<?php echo htmlspecialchars($user_data['login']); ?>" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Téléphone :</label><br>
            <div class="input-group-profil">
                <input type="text" value="<?php echo htmlspecialchars($user_data['informations']['telephone']); ?>" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>
        </fieldset>

        <fieldset class="groupe-formulaire">
            <legend>INFORMATIONS DE LIVRAISON</legend>

            <label>Adresse (N° et rue) :</label><br>
            <div class="input-group-profil">
                <input type="text" value="<?php echo htmlspecialchars($user_data['informations']['adresse']['rue']); ?>" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Complément d'adresse :</label><br>
            <div class="input-group-profil">
                <input type="text" value="<?php echo htmlspecialchars($user_data['informations']['adresse']['complement']); ?>" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Code Postal :</label><br>
            <div class="input-group-profil">
                <input type="text" value="<?php echo htmlspecialchars($user_data['informations']['adresse']['code_postal']); ?>" class="input-form input-short" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Ville :</label><br>
            <div class="input-group-profil">
                <input type="text" value="<?php echo htmlspecialchars($user_data['informations']['adresse']['ville']); ?>" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Préférences alimentaires / Allergies :</label><br>
            <div class="input-group-profil">
                <textarea rows="2" class="textarea-form" readonly><?php echo htmlspecialchars($user_data['informations']['preferences_alimentaires']); ?></textarea>
                <button type="button" class="btn-action btn-edit-profil btn-edit-tall">✏️</button>
            </div>
        </fieldset>

        <fieldset class="groupe-formulaire">
            <legend>PRÉFÉRENCES DE CONTACT</legend>
            <p class="texte-info-preferences">Offres et actualités de Flagrant Délice :</p>

            <div class="input-group-profil">
                <div class="checkbox-wrapper">
                    <?php
                    $pref_email = '';
                    if (in_array('email', $user_data['informations']['preferences_contact'])) {
                        $pref_email = 'checked';
                    }

                    $pref_sms = '';
                    if (in_array('sms', $user_data['informations']['preferences_contact'])) {
                        $pref_sms = 'checked';
                    }
                    ?>
                    <input type="checkbox" id="profil-email" <?php echo $pref_email; ?> disabled>
                    <label for="profil-email" class="label-checkbox marge-droite">Abonné par e-mail</label>

                    <input type="checkbox" id="profil-sms" <?php echo $pref_sms; ?> disabled>
                    <label for="profil-sms" class="label-checkbox">Abonné par SMS</label>
                </div>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>
        </fieldset>
    </div>

    <h2 class="categorie-titre">HISTORIQUE DES CRIMES CULINAIRES</h2>

    <div class="conteneur-tableau">
        <table class="tableau-commandes">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Détail Commande</th>
                    <th>Prix Total</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $a_des_commandes = false;
                foreach ($commandes as $commande) {

                    // Vérification que l'ID de la commande correspond à l'ID du profil qu'on affiche
                    if ($commande['id_client'] == $id_profil_a_afficher) {
                        $a_des_commandes = true;

                        // Formatage de la date
                        $date_cmd = date("d/m/Y", strtotime($commande['date_heure']));
                        $statut = $commande['statut_preparation'];

                        // Définition de la classe CSS selon le statut
                        $classe_statut = '';
                        if ($statut == 'LIVRÉ') {
                            $classe_statut = 'statut-livre';
                        } else if ($statut == 'ANNULÉ') {
                            $classe_statut = 'statut-annule';
                        }

                        echo "<tr>";
                        echo "<td>" . $date_cmd . "</td>";
                        echo "<td>Commande " . $commande['id_commande'] . "</td>";
                        echo "<td>" . number_format($commande['prix_total'], 2) . " €</td>";
                        echo "<td class='" . $classe_statut . "'>" . $statut . "</td>";

                        // Bouton de notation si la commande est livrée
                        echo "<td>";
                        if ($statut == 'LIVRÉ') {
                            echo "<a href='notation.php?id_commande=" . $commande['id_commande'] . "' class='lien-recommander'>Noter</a>";
                        } else {
                            echo "-";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                }

                if (!$a_des_commandes) {
                    echo "<tr><td colspan='5'>Aucune commande associée à ce compte.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div class="footer-col copyright-col">
            <p>©2026 Flagrant Délice</p>
        </div>
    </div>

</body>

</html>