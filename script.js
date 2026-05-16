// Fonction pour basculer le thème
function changerTheme() {
    // On récupère la balise <link> du CSS grâce à son ID
    var themeLink = document.getElementById("theme-css");
    var themeActuel = themeLink.getAttribute("href");

    var nouveauTheme = "";
    var valeurCookie = "";

    // Si on est en clair, on passe en sombre, et inversement
    if (themeActuel === "style.css") {
        nouveauTheme = "style_sombre.css";
        valeurCookie = "sombre";
    } else {
        nouveauTheme = "style.css";
        valeurCookie = "clair";
    }

    // Changement du fichier CSS dans le DOM (sans rechargement de page)
    themeLink.setAttribute("href", nouveauTheme);

    // Sauvegarde du choix dans un cookie valable 30 jours (en secondes)
    var dateExpiration = new Date();
    dateExpiration.setTime(dateExpiration.getTime() + (30 * 24 * 60 * 60 * 1000));
    document.cookie = "theme=" + valeurCookie + "; expires=" + dateExpiration.toUTCString() + "; path=/";
}