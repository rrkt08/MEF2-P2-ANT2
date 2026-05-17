// switch clair/sombre, on garde le choix dans un cookie 30j
function changerTheme() {
    var themeLink = document.getElementById("theme-css");
    var themeActuel = themeLink.getAttribute("href");

    var nouveauTheme = "";
    var valeurCookie = "";

    if (themeActuel === "style.css") {
        nouveauTheme = "style_sombre.css";
        valeurCookie = "sombre";
    } else {
        nouveauTheme = "style.css";
        valeurCookie = "clair";
    }

    themeLink.setAttribute("href", nouveauTheme);

    var dateExpiration = new Date();
    dateExpiration.setTime(dateExpiration.getTime() + (30 * 24 * 60 * 60 * 1000));
    document.cookie = "theme=" + valeurCookie + "; expires=" + dateExpiration.toUTCString() + "; path=/";
}


// icone oeil pour afficher/cacher le mdp
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


/* ======== validation inscription ======== */
function validerInscription(event) {
    var valide = true;

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

    // on reset tous les msgs d'erreur
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

    // CP : 5 chiffres
    if (champCp && (champCp.value.length !== 5 || isNaN(champCp.value))) {
        document.getElementById("erreur-cp").innerHTML = "Le code postal doit contenir exactement 5 chiffres.";
        valide = false;
    }

    // tél : 10 chiffres (espaces ok)
    if (champTel) {
        var telSansEspaces = champTel.value.replaceAll(" ", "");
        if (telSansEspaces.length !== 10 || isNaN(telSansEspaces)) {
            document.getElementById("erreur-telephone").innerHTML = "Le numéro doit contenir exactement 10 chiffres.";
            valide = false;
        }
    }

    // email : juste un @ et un .
    if (champEmail && (champEmail.value.indexOf("@") === -1 || champEmail.value.indexOf(".") === -1)) {
        document.getElementById("erreur-email").innerHTML = "Veuillez saisir une adresse e-mail valide.";
        valide = false;
    }

    // mdp 8 cara mini
    if (champMdp && champMdp.value.length < 8) {
        document.getElementById("erreur-mdp").innerHTML = "Le mot de passe doit contenir au moins 8 caractères.";
        valide = false;
    }

    // 18 ans mini
    if (champDate && champDate.value !== "") {
        var dateSaisie = new Date(champDate.value);
        var aujourdhui = new Date();

        var age = aujourdhui.getFullYear() - dateSaisie.getFullYear();

        // si anniv pas encore passé cette année
        if (aujourdhui.getMonth() < dateSaisie.getMonth() || (aujourdhui.getMonth() === dateSaisie.getMonth() && aujourdhui.getDate() < dateSaisie.getDate())) {
            age = age - 1;
        }

        if (age < 18) {
            document.getElementById("erreur-date").innerHTML = "Vous devez avoir au moins 18 ans pour commander.";
            valide = false;
        }
    }

    // case "j'ai un estomac solide" obligatoire
    if (champCertif && champCertif.checked === false) {
        document.getElementById("erreur-certification").innerHTML = "Vous devez certifier avoir un estomac solide.";
        valide = false;
    }

    if (valide === false) {
        event.preventDefault();
    }

    return valide;
}


// validation form connexion
function validerConnexion(event) {
    var valide = true;

    var champEmail = document.getElementById("email-connexion");
    var champMdp = document.getElementById("mdp-connexion");

    document.getElementById("erreur-email-connexion").innerHTML = "";
    document.getElementById("erreur-mdp-connexion").innerHTML = "";

    if (champEmail && (champEmail.value.indexOf("@") === -1 || champEmail.value.indexOf(".") === -1)) {
        document.getElementById("erreur-email-connexion").innerHTML = "Veuillez saisir une adresse e-mail valide.";
        valide = false;
    }

    if (champMdp && champMdp.value.length < 8) {
        document.getElementById("erreur-mdp-connexion").innerHTML = "Le mot de passe doit contenir au moins 8 caractères.";
        valide = false;
    }

    if (valide === false) {
        event.preventDefault();
    }

    return valide;
}


// barre de recherche accueil : pas de champ vide
function validerRecherche(event) {
    var valide = true;

    var champRecherche = document.getElementById("recherche-accueil");
    var texteErreur = document.getElementById("erreur-recherche");

    if (texteErreur) {
        texteErreur.innerHTML = "";
    }

    if (champRecherche && champRecherche.value.trim() === "") {
        if (texteErreur) {
            texteErreur.innerHTML = "Veuillez saisir le nom d'un plat avant de rechercher.";
        }
        valide = false;
    }

    if (valide === false) {
        event.preventDefault();
    }

    return valide;
}


// commentaire avis < 250 cara
function validerNotation(event) {
    var valide = true;
    var champCommentaire = document.getElementById("commentaire-avis");
    var erreurCommentaire = document.getElementById("erreur-commentaire");

    if (erreurCommentaire) erreurCommentaire.innerHTML = "";

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


// si pour plus tard, date oblig
function validerPanier(event) {
    var valide = true;

    var radioPlusTard = document.getElementById("prep_plustard");
    var champDate = document.getElementById("date_commande");
    var erreurDate = document.getElementById("erreur-date-panier");

    if (erreurDate) {
        erreurDate.innerHTML = "";
    }

    if (radioPlusTard && radioPlusTard.checked === true) {
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


// quantité ajout panier (1 à 10)
function validerAjoutPanier(event) {
    var valide = true;
    var formulaire = event.target;

    var champQte = formulaire.elements["quantite"];
    var erreurQte = formulaire.querySelector(".erreur-qte");

    if (erreurQte) {
        erreurQte.innerHTML = "";
    }

    if (champQte) {
        var quantite = parseInt(champQte.value);
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


/* ======== livreur : validation/abandon livraison en ajax ======== */
function confirmerLivraison(action) {
    var choix = confirm("Êtes-vous sûr de vouloir valider cette action ? Cette opération est définitive.");

    if (choix === false) {
        return;
    }

    var idCmd = document.getElementById("id-cmd-livraison").value;
    var msgZone = document.getElementById("message-livraison");

    var formData = new FormData();
    formData.append("id_commande", idCmd);
    formData.append("action_livraison", action);

    fetch("verif/maj_livraison.php", {
        method: "POST",
        body: formData
    })
        .then(function (reponse) { return reponse.json(); })
        .then(function (data) {
            if (data.succes === true) {
                msgZone.className = "message-alerte alerte-succes";
                msgZone.innerHTML = data.message;
                // on cache les boutons une fois validé
                var blocActions = document.getElementById("actions-livreur-bloc");
                if (blocActions) {
                    blocActions.style.display = "none";
                }
            } else {
                msgZone.className = "message-alerte alerte-erreur";
                msgZone.innerHTML = data.message;
            }
        })
        .catch(function () {
            msgZone.className = "message-alerte alerte-erreur";
            msgZone.innerHTML = "Erreur réseau, veuillez réessayer.";
        });
}


/* ======== compteurs de cara temps réel ======== */
// on prend tous les champs qui ont data-compteur="id_du_span" et on bind l'event
function initCompteursCaracteres() {
    var champs = document.querySelectorAll("[data-compteur]");

    for (var i = 0; i < champs.length; i = i + 1) {
        var champ = champs[i];
        var idCompteur = champ.getAttribute("data-compteur");
        var compteur = document.getElementById(idCompteur);

        if (compteur) {
            // closure pour bien isoler la var champ/compteur
            var maj = (function (c, cp) {
                return function () {
                    var max = c.getAttribute("maxlength");
                    if (max) {
                        cp.innerHTML = c.value.length + " / " + max + " caractères";
                    } else {
                        cp.innerHTML = c.value.length + " caractères";
                    }
                };
            })(champ, compteur);

            maj();
            champ.addEventListener("input", maj);
        }
    }
}


/* ======== profil : modif d'un champ en ajax ======== */
function modifierChampProfil(bouton) {
    var champ = bouton.parentNode.querySelector(".input-form, .textarea-form");
    var nomChamp = champ.getAttribute("data-champ");

    if (!champ || !nomChamp) {
        return;
    }

    // si le champ est readonly => on passe en mode édition
    if (champ.hasAttribute("readonly") || champ.hasAttribute("disabled")) {
        champ.removeAttribute("readonly");
        champ.removeAttribute("disabled");
        champ.focus();
        bouton.innerHTML = "✅";
        bouton.classList.add("btn-edit-valider");
    } else {
        // sinon on sauvegarde
        var nouvelleValeur = champ.value;

        // cas spécial : checkboxes pref contact
        if (nomChamp === "preferences_contact") {
            var checkboxes = bouton.parentNode.querySelectorAll("input[type='checkbox']");
            var liste = [];
            for (var i = 0; i < checkboxes.length; i = i + 1) {
                if (checkboxes[i].checked) {
                    liste.push(checkboxes[i].value);
                }
            }
            nouvelleValeur = liste.join(",");

            // on re-disable les cases
            for (var j = 0; j < checkboxes.length; j = j + 1) {
                checkboxes[j].setAttribute("disabled", "disabled");
            }
        } else {
            champ.setAttribute("readonly", "readonly");
        }

        var formData = new FormData();
        formData.append("champ", nomChamp);
        formData.append("valeur", nouvelleValeur);

        var zoneMessage = document.getElementById("message-profil");

        fetch("verif/maj_profil.php", {
            method: "POST",
            body: formData
        })
            .then(function (reponse) { return reponse.json(); })
            .then(function (data) {
                if (data.succes === true) {
                    if (zoneMessage) {
                        zoneMessage.className = "message-alerte alerte-succes";
                        zoneMessage.innerHTML = "Information mise à jour avec succès !";
                    }
                } else {
                    if (zoneMessage) {
                        zoneMessage.className = "message-alerte alerte-erreur";
                        zoneMessage.innerHTML = data.message;
                    }
                }
            })
            .catch(function () {
                if (zoneMessage) {
                    zoneMessage.className = "message-alerte alerte-erreur";
                    zoneMessage.innerHTML = "Erreur réseau lors de la sauvegarde.";
                }
            });

        bouton.innerHTML = "✏️";
        bouton.classList.remove("btn-edit-valider");
    }
}

// pour les checkboxes (pref contact) : 1er clic = activation, 2eme = save
function activerCheckboxesContact(bouton) {
    var checkboxes = bouton.parentNode.querySelectorAll("input[type='checkbox']");

    if (bouton.innerHTML === "✏️") {
        for (var i = 0; i < checkboxes.length; i = i + 1) {
            checkboxes[i].removeAttribute("disabled");
        }
        bouton.innerHTML = "✅";
        bouton.classList.add("btn-edit-valider");
    } else {
        modifierChampProfil(bouton);
    }
}


/* ======== présentation : filtres ajax + tri js ======== */
function filtrerPlatsAjax(event) {
    if (event) {
        event.preventDefault();
    }

    var categorie = document.getElementById("filtre-categorie").value;
    var allergene = document.getElementById("filtre-allergene").value;
    var recherche = document.getElementById("filtre-recherche").value;

    var formData = new FormData();
    formData.append("categorie", categorie);
    formData.append("allergene", allergene);
    formData.append("recherche", recherche);

    fetch("verif/filtrer_plats.php", {
        method: "POST",
        body: formData
    })
        .then(function (reponse) { return reponse.text(); })
        .then(function (html) {
            var zone = document.getElementById("zone-plats");
            if (zone) {
                zone.innerHTML = html;
            }
        })
        .catch(function () {
            var zone = document.getElementById("zone-plats");
            if (zone) {
                zone.innerHTML = '<p class="panier-vide-texte">Erreur lors du chargement des plats.</p>';
            }
        });

    return false;
}

// le tri est en js pur sur les data déjà chargées
function trierPlats(critere) {
    var grilles = document.querySelectorAll(".plats-populaires");

    for (var g = 0; g < grilles.length; g = g + 1) {
        var grille = grilles[g];
        var plats = grille.querySelectorAll(".plat");

        // on convertit en tableau pour pouvoir trier
        var tableauPlats = [];
        for (var i = 0; i < plats.length; i = i + 1) {
            tableauPlats.push(plats[i]);
        }

        tableauPlats.sort(function (a, b) {
            var prixA = parseFloat(a.getAttribute("data-prix"));
            var prixB = parseFloat(b.getAttribute("data-prix"));
            var nomA = a.getAttribute("data-nom");
            var nomB = b.getAttribute("data-nom");

            if (critere === "prix-asc") {
                return prixA - prixB;
            } else if (critere === "prix-desc") {
                return prixB - prixA;
            } else if (critere === "nom-asc") {
                if (nomA < nomB) return -1;
                if (nomA > nomB) return 1;
                return 0;
            }
            return 0;
        });

        // on remet les plats triés en place
        for (var j = 0; j < tableauPlats.length; j = j + 1) {
            grille.appendChild(tableauPlats[j]);
        }
    }
}


/* ======== panier : ajout/retrait qté ======== */
function modifierQuantitePanier(idPlat, delta) {
    var formData = new FormData();
    formData.append("id_plat", idPlat);
    formData.append("delta", delta);

    fetch("verif/modifier_panier.php", {
        method: "POST",
        body: formData
    })
        .then(function (reponse) { return reponse.json(); })
        .then(function (data) {
            if (data.succes === true) {
                // on reload pour voir les nouveaux totaux
                window.location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(function () {
            alert("Erreur réseau, veuillez réessayer.");
        });
}

function supprimerArticlePanier(idPlat) {
    var choix = confirm("Voulez-vous vraiment retirer cet article de votre panier ?");
    if (choix === false) {
        return;
    }

    var formData = new FormData();
    formData.append("id_plat", idPlat);
    formData.append("delta", "supprimer");

    fetch("verif/modifier_panier.php", {
        method: "POST",
        body: formData
    })
        .then(function (reponse) { return reponse.json(); })
        .then(function () {
            window.location.reload();
        })
        .catch(function () {
            alert("Erreur réseau, veuillez réessayer.");
        });
}


/* ======== cuisine : changer le statut d'une commande ======== */
function changerStatutCmd(idCmd, ligne, nouveauStatut) {
    var formData = new FormData();
    formData.append("id_commande", idCmd);
    formData.append("nouveau_statut", nouveauStatut);

    fetch("verif/maj_statut_commande.php", {
        method: "POST",
        body: formData
    })
        .then(function (reponse) { return reponse.json(); })
        .then(function (data) {
            if (data.succes === true) {
                // on retire la ligne du tableau actuel
                var ligneTr = document.getElementById("ligne-" + idCmd);
                if (ligneTr) {
                    ligneTr.parentNode.removeChild(ligneTr);
                }
                var zoneMsg = document.getElementById("message-commandes");
                if (zoneMsg) {
                    zoneMsg.className = "message-alerte alerte-succes";
                    zoneMsg.innerHTML = "Commande #" + idCmd + " mise à jour : " + nouveauStatut;
                }
            } else {
                alert(data.message);
            }
        })
        .catch(function () {
            alert("Erreur réseau, veuillez réessayer.");
        });
}


/* ======== détail commande : sauver statut + livreur ======== */
function validerMiseAJour(event) {
    if (event) {
        event.preventDefault();
    }

    var valide = true;
    var champStatut = document.getElementById("nouveau_statut");
    var champLivreur = document.getElementById("id_livreur");
    var champIdCmd = document.getElementById("id_cmd_hidden");

    var erreurStatut = document.getElementById("erreur-statut");
    var erreurLivreur = document.getElementById("erreur-livreur");
    var zoneMsg = document.getElementById("message-details");

    if (erreurStatut) erreurStatut.innerHTML = "";
    if (erreurLivreur) erreurLivreur.innerHTML = "";

    if (champStatut && champLivreur) {
        if (champStatut.value === "EN LIVRAISON" && champLivreur.value === "") {
            if (erreurLivreur) {
                erreurLivreur.innerHTML = "Veuillez attribuer un livreur.";
            }
            valide = false;
        }
    }

    if (valide === false) {
        return false;
    }

    var formData = new FormData();
    formData.append("id_commande", champIdCmd.value);
    formData.append("nouveau_statut", champStatut.value);
    formData.append("id_livreur", champLivreur.value);

    fetch("verif/maj_statut_commande.php", {
        method: "POST",
        body: formData
    })
        .then(function (reponse) { return reponse.json(); })
        .then(function (data) {
            if (data.succes === true) {
                if (zoneMsg) {
                    zoneMsg.className = "message-alerte alerte-succes";
                    zoneMsg.innerHTML = "Mise à jour réussie !";
                }
            } else {
                if (zoneMsg) {
                    zoneMsg.className = "message-alerte alerte-erreur";
                    zoneMsg.innerHTML = data.message;
                }
            }
        })
        .catch(function () {
            if (zoneMsg) {
                zoneMsg.className = "message-alerte alerte-erreur";
                zoneMsg.innerHTML = "Erreur réseau, veuillez réessayer.";
            }
        });

    return false;
}


/* ======== admin : bloquer / débloquer ======== */
function bloquerUtilisateurAjax(idUser, bouton) {
    var actionActuelle = bouton.getAttribute("data-action");

    var message = "";
    if (actionActuelle === "bloquer") {
        message = "Voulez-vous vraiment bloquer cet utilisateur ? Sa session active sera terminée immédiatement.";
    } else {
        message = "Voulez-vous débloquer cet utilisateur ?";
    }

    var choix = confirm(message);
    if (choix === false) {
        return;
    }

    var formData = new FormData();
    formData.append("id_utilisateur", idUser);
    formData.append("action", actionActuelle);

    fetch("verif/bloquer_utilisateur.php", {
        method: "POST",
        body: formData
    })
        .then(function (reponse) { return reponse.json(); })
        .then(function (data) {
            if (data.succes === true) {
                // on switch le bouton bloqué <-> débloqué
                if (actionActuelle === "bloquer") {
                    bouton.innerHTML = "DÉBLOQUER";
                    bouton.setAttribute("data-action", "debloquer");
                    bouton.classList.remove("btn-bloquer");
                    bouton.classList.add("btn-debloquer");
                    // on met aussi à jour la cellule "état"
                    var tr = bouton.closest("tr");
                    if (tr) {
                        var cellEtat = tr.children[4];
                        if (cellEtat) {
                            cellEtat.innerHTML = "BLOQUÉ";
                            cellEtat.className = "statut-annule";
                        }
                    }
                } else {
                    bouton.innerHTML = "BLOQUER";
                    bouton.setAttribute("data-action", "bloquer");
                    bouton.classList.remove("btn-debloquer");
                    bouton.classList.add("btn-bloquer");
                    var tr2 = bouton.closest("tr");
                    if (tr2) {
                        var cellEtat2 = tr2.children[4];
                        if (cellEtat2) {
                            cellEtat2.innerHTML = "ACTIF";
                            cellEtat2.className = "statut-livre";
                        }
                    }
                }

                var zoneMsg = document.getElementById("message-admin");
                if (zoneMsg) {
                    zoneMsg.className = "message-alerte alerte-succes";
                    zoneMsg.innerHTML = data.message;
                }
            } else {
                alert(data.message);
            }
        })
        .catch(function () {
            alert("Erreur réseau, veuillez réessayer.");
        });
}


/* ======== admin : changer statut fidélité ======== */
function changerStatutFidelite(idUser) {
    if (!confirm("Faire passer cet utilisateur au statut fidélité suivant ?")) {
        return;
    }

    var formData = new FormData();
    formData.append("id_utilisateur", idUser);
    formData.append("action", "statut");

    fetch("verif/maj_fidelite.php", {
        method: "POST",
        body: formData
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            var zoneMsg = document.getElementById("message-admin");
            if (data.succes === true) {
                if (zoneMsg) {
                    zoneMsg.className = "message-alerte alerte-succes";
                    zoneMsg.innerHTML = data.message;
                }
            } else {
                if (zoneMsg) {
                    zoneMsg.className = "message-alerte alerte-erreur";
                    zoneMsg.innerHTML = data.message;
                } else {
                    alert(data.message);
                }
            }
        })
        .catch(function () {
            alert("Erreur réseau, veuillez réessayer.");
        });
}


/* ======== admin : accorder une remise (en points) ======== */
function accorderRemise(idUser) {
    var saisie = prompt("Combien de points fidélité ajouter ? (peut être négatif pour retirer)", "50");
    if (saisie === null) {
        return;
    }

    var pts = parseInt(saisie);
    if (isNaN(pts)) {
        alert("Valeur invalide.");
        return;
    }

    var formData = new FormData();
    formData.append("id_utilisateur", idUser);
    formData.append("action", "remise");
    formData.append("valeur", pts);

    fetch("verif/maj_fidelite.php", {
        method: "POST",
        body: formData
    })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            var zoneMsg = document.getElementById("message-admin");
            if (data.succes === true) {
                if (zoneMsg) {
                    zoneMsg.className = "message-alerte alerte-succes";
                    zoneMsg.innerHTML = data.message;
                }
            } else {
                alert(data.message);
            }
        })
        .catch(function () {
            alert("Erreur réseau, veuillez réessayer.");
        });
}


/* ======== check si on s'est fait bloquer entre temps ======== */
// (poll toutes les 30s sur les pages connectées)
function verifierBlocage() {
    fetch("verif/verifier_blocage.php")
        .then(function (reponse) { return reponse.json(); })
        .then(function (data) {
            if (data.bloque === true) {
                alert("Votre compte a été bloqué par un administrateur. Vous allez être déconnecté.");
                window.location.href = "verif/deconnexion.php";
            }
        })
        .catch(function () {
            // on dit rien, ça ré-essayera
        });
}


// init au chargement de la page
window.addEventListener("load", function () {
    initCompteursCaracteres();

    // si on est co, on check périodiquement si on a été bloqué
    if (document.body.getAttribute("data-connecte") === "1") {
        setInterval(verifierBlocage, 30000);
    }
});
