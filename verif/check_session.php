<?php
// Phase 3 : à inclure en haut de chaque page protégée par session.
// Si l'utilisateur a été bloqué entre-temps, on détruit sa session et on redirige.

if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte'] == true) {

    $fichier_users = __DIR__ . '/../data/utilisateurs.json';

    if (file_exists($fichier_users)) {
        $tous = json_decode(file_get_contents($fichier_users), true);

        foreach ($tous as $u) {
            if ($u['id_utilisateur'] == $_SESSION['id_utilisateur']) {
                if (isset($u['bloque']) && $u['bloque'] == true) {
                    // Compte bloqué => fin de session immédiate
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
