<?php
session_start();

if (!isset($_SESSION['utilisateur_connecte'])) {
    header("Location: connexion.php");
    exit();
}

require_once('verif/check_session.php');

// quel profil afficher ?
if ($_SESSION['role'] == "admin") {
    // admin qui regarde un autre profil via "VOIR PROFIL"
    if (isset($_GET['id'])) {
        $id_profil_a_afficher = (int)$_GET['id'];
    } else {
        $id_profil_a_afficher = $_SESSION['id_utilisateur'];
    }
} else if ($_SESSION['role'] == "client") {
    $id_profil_a_afficher = $_SESSION['id_utilisateur'];
} else {
    header("Location: connexion.php");
    exit();
}

// seul le client peut modifier son profil
// (l'admin a juste un droit de regard ici)
$peut_modifier = false;
if ($_SESSION['role'] == "client") {
    $peut_modifier = true;
}

// charge les data
$utilisateurs = json_decode(file_get_contents('data/utilisateurs.json'), true);
if ($utilisateurs === null) {
    $utilisateurs = [];
}

$commandes = json_decode(file_get_contents('data/commandes.json'), true);
if ($commandes === null) {
    $commandes = [];
}

// trouve l'user
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
    <title>Flagrant Délice - Mon Profil</title>
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
            <li><a href="profil.php" class="actif">MON COMPTE</a></li>
            <?php
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

    <?php
    // messages divers (retour de paiement, retour notation, etc)
    if (isset($_GET['commande']) && $_GET['commande'] == 'succes') {
        echo '<div class="message-alerte alerte-succes">Votre commande a bien été enregistrée. Merci !</div>';
    }
    if (isset($_GET['succes_avis'])) {
        echo '<div class="message-alerte alerte-succes">Merci pour votre avis !</div>';
    }
    if (isset($_GET['erreur_avis'])) {
        if ($_GET['erreur_avis'] == 'deja_note') {
            echo '<div class="message-alerte alerte-erreur">Vous avez déjà noté cette commande.</div>';
        } else {
            echo '<div class="message-alerte alerte-erreur">Cette commande ne peut pas être notée.</div>';
        }
    }
    if (isset($_GET['modif_commande']) && $_GET['modif_commande'] == 'succes') {
        echo '<div class="message-alerte alerte-succes">Commande modifiée avec succès, complément payé.</div>';
    }
    ?>

    <!-- la zone où on affiche les retours ajax (succès / erreur de modif) -->
    <div id="message-profil"></div>

    <div class="conteneur-formulaire">

        <?php
        // que le client a une fidélité (les admins/restaurateurs/livreurs non)
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
                <input type="text" data-champ="nom" maxlength="40" data-compteur="cpt-profil-nom" value="<?php echo htmlspecialchars($user_data['informations']['nom']); ?>" class="input-form" readonly>
                <?php if ($peut_modifier): ?>
                    <button type="button" class="btn-action btn-edit-profil" onclick="modifierChampProfil(this)">✏️</button>
                <?php endif; ?>
            </div>
            <span id="cpt-profil-nom" class="compteur-caracteres"></span>

            <label>Prénom :</label><br>
            <div class="input-group-profil">
                <input type="text" data-champ="prenom" maxlength="40" data-compteur="cpt-profil-prenom" value="<?php echo htmlspecialchars($user_data['informations']['prenom']); ?>" class="input-form" readonly>
                <?php if ($peut_modifier): ?>
                    <button type="button" class="btn-action btn-edit-profil" onclick="modifierChampProfil(this)">✏️</button>
                <?php endif; ?>
            </div>
            <span id="cpt-profil-prenom" class="compteur-caracteres"></span>

            <label>Adresse E-mail :</label><br>
            <div class="input-group-profil">
                <input type="email" data-champ="login" maxlength="60" data-compteur="cpt-profil-email" value="<?php echo htmlspecialchars($user_data['login']); ?>" class="input-form" readonly>
                <?php if ($peut_modifier): ?>
                    <button type="button" class="btn-action btn-edit-profil" onclick="modifierChampProfil(this)">✏️</button>
                <?php endif; ?>
            </div>
            <span id="cpt-profil-email" class="compteur-caracteres"></span>

            <label>Téléphone :</label><br>
            <div class="input-group-profil">
                <input type="text" data-champ="telephone" maxlength="14" data-compteur="cpt-profil-tel" value="<?php echo htmlspecialchars($user_data['informations']['telephone']); ?>" class="input-form" readonly>
                <?php if ($peut_modifier): ?>
                    <button type="button" class="btn-action btn-edit-profil" onclick="modifierChampProfil(this)">✏️</button>
                <?php endif; ?>
            </div>
            <span id="cpt-profil-tel" class="compteur-caracteres"></span>
        </fieldset>

        <fieldset class="groupe-formulaire">
            <legend>INFORMATIONS DE LIVRAISON</legend>

            <label>Adresse (N° et rue) :</label><br>
            <div class="input-group-profil">
                <input type="text" data-champ="rue" maxlength="80" data-compteur="cpt-profil-rue" value="<?php echo htmlspecialchars($user_data['informations']['adresse']['rue']); ?>" class="input-form" readonly>
                <?php if ($peut_modifier): ?>
                    <button type="button" class="btn-action btn-edit-profil" onclick="modifierChampProfil(this)">✏️</button>
                <?php endif; ?>
            </div>
            <span id="cpt-profil-rue" class="compteur-caracteres"></span>

            <label>Complément d'adresse :</label><br>
            <div class="input-group-profil">
                <input type="text" data-champ="complement" maxlength="100" data-compteur="cpt-profil-comp" value="<?php echo htmlspecialchars($user_data['informations']['adresse']['complement']); ?>" class="input-form" readonly>
                <?php if ($peut_modifier): ?>
                    <button type="button" class="btn-action btn-edit-profil" onclick="modifierChampProfil(this)">✏️</button>
                <?php endif; ?>
            </div>
            <span id="cpt-profil-comp" class="compteur-caracteres"></span>

            <label>Code Postal :</label><br>
            <div class="input-group-profil">
                <input type="text" data-champ="code_postal" maxlength="5" data-compteur="cpt-profil-cp" value="<?php echo htmlspecialchars($user_data['informations']['adresse']['code_postal']); ?>" class="input-form input-short" readonly>
                <?php if ($peut_modifier): ?>
                    <button type="button" class="btn-action btn-edit-profil" onclick="modifierChampProfil(this)">✏️</button>
                <?php endif; ?>
            </div>
            <span id="cpt-profil-cp" class="compteur-caracteres"></span>

            <label>Ville :</label><br>
            <div class="input-group-profil">
                <input type="text" data-champ="ville" maxlength="40" data-compteur="cpt-profil-ville" value="<?php echo htmlspecialchars($user_data['informations']['adresse']['ville']); ?>" class="input-form" readonly>
                <?php if ($peut_modifier): ?>
                    <button type="button" class="btn-action btn-edit-profil" onclick="modifierChampProfil(this)">✏️</button>
                <?php endif; ?>
            </div>
            <span id="cpt-profil-ville" class="compteur-caracteres"></span>

            <label>Préférences alimentaires / Allergies :</label><br>
            <div class="input-group-profil">
                <textarea rows="2" data-champ="preferences_alimentaires" class="textarea-form" maxlength="250" data-compteur="cpt-profil-pref" readonly><?php echo htmlspecialchars($user_data['informations']['preferences_alimentaires']); ?></textarea>
                <?php if ($peut_modifier): ?>
                    <button type="button" class="btn-action btn-edit-profil btn-edit-tall" onclick="modifierChampProfil(this)">✏️</button>
                <?php endif; ?>
            </div>
            <span id="cpt-profil-pref" class="compteur-caracteres"></span>
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
                    <input type="checkbox" id="profil-email" value="email" <?php echo $pref_email; ?> disabled>
                    <label for="profil-email" class="label-checkbox marge-droite">Abonné par e-mail</label>

                    <input type="checkbox" id="profil-sms" value="sms" <?php echo $pref_sms; ?> disabled>
                    <label for="profil-sms" class="label-checkbox">Abonné par SMS</label>

                    <!-- champ caché juste pour qu'on retrouve le nom data-champ -->
                    <input type="hidden" data-champ="preferences_contact" class="input-form" value="">
                </div>
                <?php if ($peut_modifier): ?>
                    <button type="button" class="btn-action btn-edit-profil" onclick="activerCheckboxesContact(this)">✏️</button>
                <?php endif; ?>
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

                    if ($commande['id_client'] == $id_profil_a_afficher) {
                        $a_des_commandes = true;

                        $date_cmd = date("d/m/Y", strtotime($commande['date_heure']));
                        $statut = $commande['statut_preparation'];

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

                        // notation seulement si livré + livraison + pas déjà noté
                        echo "<td>";
                        $deja_note = (isset($commande['deja_note']) && $commande['deja_note'] == true);

                        if ($statut == 'LIVRÉ' && $commande['lieu_consommation'] == 'livraison' && !$deja_note) {
                            echo "<a href='notation.php?id_commande=" . $commande['id_commande'] . "' class='lien-recommander'>Noter</a>";
                        } elseif ($deja_note) {
                            echo "<span class='lien-note-fait'>✓ Noté</span>";
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
    <script src="script.js"></script>
</body>

</html>
