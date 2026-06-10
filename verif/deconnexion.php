<?php
session_start();

// on vide toutes les variables de session
session_unset();

// puis on détruit la session
session_destroy();

header("Location: ../connexion.php");
exit();
?>
