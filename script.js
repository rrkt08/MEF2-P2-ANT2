function changerTheme() {
    var themeLink = document.getElementById("theme-css");
    var themeActuel = themeLink.getAttribute("href");

    var nouveauTheme = "";
    var valeurCookie = "";

    //Si on est en lightmode on switch en darkmode...
    if (themeActuel === "style.css") {
        nouveauTheme = "style_sombre.css";
        valeurCookie = "sombre";
    } else {
        nouveauTheme = "style.css";
        valeurCookie = "clair";
    }

    themeLink.setAttribute("href", nouveauTheme);

    //La sauvegarde dans un cookie dure 30j
    var dateExpiration = new Date();
    dateExpiration.setTime(dateExpiration.getTime() + (30 * 24 * 60 * 60 * 1000));
    document.cookie = "theme=" + valeurCookie + "; expires=" + dateExpiration.toUTCString() + "; path=/";
}

function afficherMasquerMdp(idChamp, idIcone) {
    var champMdp = document.getElementById(idChamp);
    var iconeOeil = document.getElementById(idIcone);

    if (champMdp && iconeOeil) {
        if (champMdp.type === "password") {
            champMdp.type = "text";
            iconeOeil.textContent = "🙈";
        } else {
            champMdp.type = "password";
            iconeOeil.textContent = "👁️";
        }
    }
}

function validerInscription(event) {
    var valide = true;

    //Récupération des id
    var champNom = document.getElementById("nom-insc");
    var champPrenom = document.getElementById("prenom-insc");
    var champDate = document.getElementById("date-naissance-insc");
    var champAdresse = document.getElementById("adresse-insc");
    var champCp = document.getElementById("cp-insc");
    var champVille = document.getElementById("ville-insc");
    var champTel = document.getElementById("tel-insc");
    var champEmail = document.getElementById("email-insc");
    var champMdp = document.getElementById("mdp-insc");
    var champCertif = document.getElementById("age");

    //On vide les messages d'erreur
    document.getElementById("erreur-nom").innerHTML = "";
    document.getElementById("erreur-prenom").innerHTML = "";
    document.getElementById("erreur-date").innerHTML = "";
    document.getElementById("erreur-adresse").innerHTML = "";
    document.getElementById("erreur-cp").innerHTML = "";
    document.getElementById("erreur-ville").innerHTML = "";
    document.getElementById("erreur-telephone").innerHTML = "";
    document.getElementById("erreur-email").innerHTML = "";
    document.getElementById("erreur-mdp").innerHTML = "";
    document.getElementById("erreur-certification").innerHTML = "";

    //Vérifs
    if (champNom && champNom.value.length < 2) {
        document.getElementById("erreur-nom").innerHTML = "Le nom est trop court.";
        valide = false;
    }
    if (champPrenom && champPrenom.value.length < 2) {
        document.getElementById("erreur-prenom").innerHTML = "Le prénom est trop court.";
        valide = false;
    }
    if (champAdresse && champAdresse.value.length < 5) {
        document.getElementById("erreur-adresse").innerHTML = "L'adresse est trop courte.";
        valide = false;
    }
    if (champVille && champVille.value.length < 2) {
        document.getElementById("erreur-ville").innerHTML = "Le nom de la ville est trop court.";
        valide = false;
    }

    //code postal, 5 caractères, que des chiffres
    if (champCp && (champCp.value.length !== 5 || isNaN(champCp.value))) {
        document.getElementById("erreur-cp").innerHTML = "Le code postal doit contenir exactement 5 chiffres.";
        valide = false;
    }

    //Téléphone, 10 chiffres
    if (champTel) {
        //numéro sans espace
        var telSansEspaces = champTel.value.replaceAll(" ", "");

        if (telSansEspaces.length !== 10 || isNaN(telSansEspaces)) {
            document.getElementById("erreur-telephone").innerHTML = "Le numéro doit contenir exactement 10 chiffres.";
            valide = false;
        }
    }

    //E-mail, un "@" et un "." 
    if (champEmail && (champEmail.value.indexOf("@") === -1 || champEmail.value.indexOf(".") === -1)) {
        document.getElementById("erreur-email").innerHTML = "Veuillez saisir une adresse e-mail valide.";
        valide = false;
    }

    //Mdp, 8 caractères minimum
    if (champMdp && champMdp.value.length < 8) {
        document.getElementById("erreur-mdp").innerHTML = "Le mot de passe doit contenir au moins 8 caractères.";
        valide = false;
    }

    //date de naissance, il faut avoir 18 ans pour commander en ligne
    if (champDate && champDate.value !== "") {
        var dateSaisie = new Date(champDate.value);
        var aujourdhui = new Date();

        var anneeSaisie = dateSaisie.getFullYear();
        var anneeAujourdhui = aujourdhui.getFullYear();
        var age = anneeAujourdhui - anneeSaisie;

        // On ajuste si le mois d'anniversaire n'est pas encore passé
        if (aujourdhui.getMonth() < dateSaisie.getMonth() || (aujourdhui.getMonth() === dateSaisie.getMonth() && aujourdhui.getDate() < dateSaisie.getDate())) {
            age = age - 1;
        }

        if (age < 18) {
            document.getElementById("erreur-date").innerHTML = "Vous devez avoir au moins 18 ans pour commander.";
            valide = false;
        }
    }

    //case à cocher a la fin du formulaire
    if (champCertif && champCertif.checked === false) {
        document.getElementById("erreur-certification").innerHTML = "Vous devez certifier avoir un estomac solide.";
        valide = false;
    }

    //une règle pas respectée = on annule l'envoi
    if (valide === false) {
        event.preventDefault();
    }

    return valide;
}

function validerConnexion(event) {
    var valide = true;

    //Récupération des id
    var champEmail = document.getElementById("email-connexion");
    var champMdp = document.getElementById("mdp-connexion");

    //On vide les messages d'erreur
    document.getElementById("erreur-email-connexion").innerHTML = "";
    document.getElementById("erreur-mdp-connexion").innerHTML = "";

    //E-mail, un "@" et un "." 
    if (champEmail && (champEmail.value.indexOf("@") === -1 || champEmail.value.indexOf(".") === -1)) {
        document.getElementById("erreur-email-connexion").innerHTML = "Veuillez saisir une adresse e-mail valide.";
        valide = false;
    }

    //Mdp, 8 caractères minimum
    if (champMdp && champMdp.value.length < 8) {
        document.getElementById("erreur-mdp-connexion").innerHTML = "Le mot de passe doit contenir au moins 8 caractères.";
        valide = false;
    }

    //une règle pas respectée = on annule l'envoi
    if (valide === false) {
        event.preventDefault();
    }

    return valide;
}

//Pour la page d'accueil
function validerRecherche(event) {
    var valide = true;

    var champRecherche = document.getElementById("recherche-accueil");
    var texteErreur = document.getElementById("erreur-recherche");

    if (texteErreur) {
        texteErreur.innerHTML = "";
    }

    //champ vide ou ne contenant que des espaces
    if (champRecherche && champRecherche.value.trim() === "") {
        if (texteErreur) {
            texteErreur.innerHTML = "Veuillez saisir le nom d'un plat avant de rechercher.";
        }
        valide = false;
    }

    //une règle pas respectée = on annule l'envoi
    if (valide === false) {
        event.preventDefault();
    }

    return valide;
}

/*pour la page détails_commande*/
function validerMiseAJour(event) {
    var valide = true;

    var champStatut = document.getElementById("nouveau_statut");
    var champLivreur = document.getElementById("id_livreur");

    var erreurStatut = document.getElementById("erreur-statut");
    var erreurLivreur = document.getElementById("erreur-livreur");

    if (erreurStatut) erreurStatut.innerHTML = "";
    if (erreurLivreur) erreurLivreur.innerHTML = "";

    //"en livraison" sélectionné = un livreur obligatoire
    if (champStatut && champLivreur) {
        if (champStatut.value === "EN LIVRAISON" && champLivreur.value === "") {
            if (erreurLivreur) {
                erreurLivreur.innerHTML = "Veuillez attribuer un livreur.";
            }
            valide = false;
        }
    }

    //une règle pas respectée = on annule l'envoi
    if (valide === false) {
        event.preventDefault();
    }

    return valide;
}

function confirmerLivraison(event) {
    //La fonction confirm bloque l'écran et pose une question au livreur
    var choix = confirm("Êtes-vous sûr de vouloir valider cette action ? Cette opération est définitive.");

    //Abandonnée => choix = false => l'envoi du formulaire est bloqué
    if (choix === false) {
        event.preventDefault();
        return false;
    }

    //Livraison terminée => le formulaire s'envoie
    return true;
}

function validerNotation(event) {
    var valide = true;
    var champCommentaire = document.getElementById("commentaire-avis");
    var erreurCommentaire = document.getElementById("erreur-commentaire");

    if (erreurCommentaire) erreurCommentaire.innerHTML = "";

    //commentaire < 250 caractères
    if (champCommentaire && champCommentaire.value.length > 250) {
        if (erreurCommentaire) {
            erreurCommentaire.innerHTML = "Votre commentaire est trop long (250 caractères maximum).";
        }
        valide = false;
    }

    if (valide === false) {
        event.preventDefault();
    }

    return valide;
}

function validerPanier(event) {
    var valide = true;

    var radioPlusTard = document.getElementById("prep_plustard");
    var champDate = document.getElementById("date_commande");
    var erreurDate = document.getElementById("erreur-date-panier");

    if (erreurDate) {
        erreurDate.innerHTML = "";
    }

    if (radioPlusTard && radioPlusTard.checked === true) {
        //date vide
        if (champDate && champDate.value === "") {
            if (erreurDate) {
                erreurDate.innerHTML = "Veuillez choisir une date et une heure avant de payer.";
            }
            valide = false;
        }
    }

    if (valide === false) {
        event.preventDefault();
    }

    return valide;
}

/*pour la page presentation*/
function validerAjoutPanier(event) {
    var valide = true;

    //"event.target" = formulaire qui vient d'être soumis
    var formulaire = event.target;

    var champQte = formulaire.elements["quantite"];
    var erreurQte = formulaire.querySelector(".erreur-qte");

    if (erreurQte) {
        erreurQte.innerHTML = "";
    }

    if (champQte) {
        var quantite = parseInt(champQte.value);

        //1 < nbr < 10
        if (isNaN(quantite) || quantite < 1 || quantite > 10) {
            if (erreurQte) {
                erreurQte.innerHTML = "Quantité invalide (1 à 10 max).";
            }
            valide = false;
        }
    }

    if (valide === false) {
        event.preventDefault();
    }

    return valide;
}