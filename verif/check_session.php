<?php
// ce fichier est inclus en haut des pages qui nécessitent une connexion
// il vérifie si le compte a été bloqué pendant que l'utilisateur était connecté

if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte'] == true) {

    $fichier_users = __DIR__ . '/../data/utilisateurs.json';

    if (file_exists($fichier_users)) {
        $tous = json_decode(file_get_contents($fichier_users), true);

        foreach ($tous as $u) {
            if ($u['id_utilisateur'] == $_SESSION['id_utilisateur']) {
                if (isset($u['bloque']) && $u['bloque'] == true) {
                    // on détruit la session et on redirige vers la connexion
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
