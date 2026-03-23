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
            <li><a href="connexion.php">DÉCONNEXION</a></li>
        </ul>
    </div>

    <div class="bandeau-titre">
        <h2><u>MON ESPACE PERSONNEL</u></h2>
    </div>

    <div class="conteneur-formulaire">

        <fieldset class="groupe-formulaire fidelite-box">
            <legend>MA FIDÉLITÉ</legend>
            <div class="fidelite-content">
                <h3 class="fidelite-titre">STATUT : COMPLICE GOURMAND</h3>
                <p class="fidelite-points">Points cumulés : <span class="fidelite-valeur">150 pts</span></p>
                <p class="fidelite-desc">Plus que 50 points pour obtenir un <em>Burger Donut offert</em> !</p>
            </div>
        </fieldset>

        <fieldset class="groupe-formulaire">
            <legend>MES INFORMATIONS</legend>

            <label>Prénom :</label><br>
            <div class="input-group-profil">
                <input type="text" value="Tristan" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Nom :</label><br>
            <div class="input-group-profil">
                <input type="text" value="Douille" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Date de naissance :</label><br>
            <div class="input-group-profil">
                <input type="date" value="1998-08-15" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>E-mail :</label><br>
            <div class="input-group-profil">
                <input type="email" value="tristan.douille@email.com" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>

            <label>Mot de passe :</label><br>
            <div class="input-group-profil">
                <input type="password" value="********" class="input-form" readonly>
                <button type="button" class="btn-action btn-edit-profil">✏️</button>
            </div>
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
                <tr>
                    <td>12/10/2025</td>
                    <td>2x Pizza Hawaïenne<br>1x Soda Cornichon</td>
                    <td>22.00 €</td>
                    <td class="statut-livre">LIVRÉ</td>
                    <td><a href="presentation.php" class="lien-recommander">Recommander</a></td>
                </tr>
                <tr>
                    <td>05/10/2025</td>
                    <td>1x Fish Donut<br>1x Frites Chocolat</td>
                    <td>15.00 €</td>
                    <td class="statut-livre">LIVRÉ</td>
                    <td><a href="presentation.php" class="lien-recommander">Recommander</a></td>
                </tr>
                <tr>
                    <td>20/09/2025</td>
                    <td>1x Omelette Skittles</td>
                    <td>4.50 €</td>
                    <td class="statut-annule">ANNULÉ</td>
                    <td>-</td>
                </tr>
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