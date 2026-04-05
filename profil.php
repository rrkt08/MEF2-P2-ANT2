<?php
session_start();

// Vérification de la connexion
if (!isset($_SESSION['utilisateur_connecte']) || $_SESSION['role'] != 'client') {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['id_utilisateur']; // On utilise l'ID pour faire le lien avec le JSON

// Récupération des informations de l'utilisateur connecté
$profil_client = null;
if (file_exists('data/utilisateurs.json')) {
    $utilisateurs = json_decode(file_get_contents('data/utilisateurs.json'), true);
    foreach ($utilisateurs as $user) {
        if ($user['id_utilisateur'] == $user_id) {
            $profil_client = $user;
            break;
        }
    }
}

// Récupération des commandes
$commandes = [];
if (file_exists('data/commandes.json')) {
    $commandes = json_decode(file_get_contents('data/commandes.json'), true);
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
            <li><a href="verif/deconnexion.php">DÉCONNEXION</a></li>
        </ul>
    </div>

    <div class="bandeau-titre">
        <h2><u>MON ESPACE PERSONNEL</u></h2>
    </div>

    <div class="conteneur-formulaire">

        <fieldset class="groupe-formulaire fidelite-box">
            <legend>MA FIDÉLITÉ</legend>
            <div class="fidelite-content">
                <h3 class="fidelite-titre">STATUT : <?php echo htmlspecialchars($profil_client['fidelite']['statut'] ?? 'NOUVEAU'); ?></h3>
                <p class="fidelite-points">Points cumulés : <span class="fidelite-valeur"><?php echo htmlspecialchars($profil_client['fidelite']['points'] ?? 0); ?></span></p>
            </div>
        </fieldset>

        <fieldset class="groupe-formulaire">
            <legend>MES INFORMATIONS ✏️</legend>
            
            <label>Nom :</label><br>
            <input type="text" value="<?php echo htmlspecialchars($profil_client['informations']['nom']); ?>" class="input-form" readonly><br><br>

            <label>Prénom :</label><br>
            <input type="text" value="<?php echo htmlspecialchars($profil_client['informations']['prenom']); ?>" class="input-form" readonly><br><br>

            <label>Adresse E-mail :</label><br>
            <input type="email" value="<?php echo htmlspecialchars($profil_client['login']); ?>" class="input-form" readonly><br><br>

            <label>Téléphone :</label><br>
            <input type="text" value="<?php echo htmlspecialchars($profil_client['informations']['telephone']); ?>" class="input-form" readonly><br><br>

            <label>Adresse de livraison :</label><br>
            <input type="text" value="<?php echo htmlspecialchars($profil_client['informations']['adresse']['rue'] . ', ' . $profil_client['informations']['adresse']['code_postal'] . ' ' . $profil_client['informations']['adresse']['ville']); ?>" class="input-form" readonly><br><br>
        </fieldset>

        <fieldset class="groupe-formulaire">
            <legend>MA LIVRAISON</legend>

            <label>Adresse (N° et rue) :</label><br>
            <div class="input-group-profil">
                <input type="text" value="12 Rue du Mauvais Goût" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Complément d'adresse :</label><br>
            <div class="input-group-profil">
                <input type="text" value="Bâtiment B, Interphone B421" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Code Postal :</label><br>
            <div class="input-group-profil">
                <input type="text" value="95000" class="input-form input-short" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Ville :</label><br>
            <div class="input-group-profil">
                <input type="text" value="Cergy" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Numéro de téléphone :</label><br>
            <div class="input-group-profil">
                <input type="tel" value="06 12 34 56 78" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Préférences alimentaires / Allergies :</label><br>
            <div class="input-group-profil">
                <textarea rows="2" class="textarea-form" readonly>Intolérance au lactose, pas de cornichons svp.</textarea>
                <button type="button" class="btn-action btn-edit-profil btn-edit-tall">✏️</button>
            </div>
        </fieldset>

        <fieldset class="groupe-formulaire">
            <legend>MES PRÉFÉRENCES</legend>
            <p class="texte-info-preferences">Offres et actualités de Flagrant Délice :</p>

            <div class="input-group-profil">
                <div class="checkbox-wrapper">
                    <input type="checkbox" id="profil-email" checked disabled>
                    <label for="profil-email" class="label-checkbox marge-droite">Abonné par e-mail</label>

                    <input type="checkbox" id="profil-sms" disabled>
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
                    if ($commande['id_client'] == $user_id) {
                        $a_des_commandes = true;
                        
                        // Formatage de la date
                        $date_cmd = date("d/m/Y", strtotime($commande['date_heure']));
                        $statut = $commande['statut_preparation'];
                        
                        // Définir la classe CSS selon le statut (pour garder tes couleurs)
                        $classe_statut = ($statut == 'LIVRÉ') ? 'statut-livre' : (($statut == 'ANNULÉ') ? 'statut-annule' : '');

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
                    echo "<tr><td colspan='5'>Vous n'avez passé aucune commande.</td></tr>";
                }
                ?>
            </tbody>
        </table>
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