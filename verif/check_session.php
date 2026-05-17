<?php
// petit fichier à inclure en haut des pages connectées
// si l'admin nous a bloqué pendant qu'on était co, on dégage

if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte'] == true) {

    $fichier_users = __DIR__ . '/../data/utilisateurs.json';

    if (file_exists($fichier_users)) {
        $tous = json_decode(file_get_contents($fichier_users), true);

        foreach ($tous as $u) {
            if ($u['id_utilisateur'] == $_SESSION['id_utilisateur']) {
                if (isset($u['bloque']) && $u['bloque'] == true) {
                    // session terminée direct
                    session_unset();
                    session_destroy();
                    header("Location: connexion.php?erreur=bloque");
                    exit();
                }
                break;
            }
        }
    }
}
?>
