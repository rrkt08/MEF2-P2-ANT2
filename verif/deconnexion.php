<?php
session_start();

// On vide les variables
session_unset();

// Destruction de la session
session_destroy();

// Redirection
header("Location: ../connexion.php");
exit();
