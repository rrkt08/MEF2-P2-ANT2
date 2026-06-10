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

    // on change le fichier css sans recharger la page
    themeLink.setAttribute("href", nouveauTheme);

    // et on sauvegarde le choix dans un cookie qui dure 30 jours
    var dateExpiration = new Date();
    dateExpiration.setTime(dateExpiration.getTime() + (30 * 24 * 60 * 60 * 1000));
    document.cookie = "theme=" + valeurCookie + "; expires=" + dateExpiration.toUTCString() + "; path=/";
}


// icone oeil pour afficher/cacher le mot de passe
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


// validation du formulaire d'inscription côté client
// ça évite d'envoyer des données invalides au serveur pour rien
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

    // on efface les anciens messages d'erreur avant de re-vérifier
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

    // code postal : exactement 5 chiffres
    if (champCp && (champCp.value.length !== 5 || isNaN(champCp.value))) {
        document.getElementById("erreur-cp").innerHTML = "Le code postal doit contenir exactement 5 chiffres.";
        valide = false;
    }

    // téléphone : 10 chiffres, les espaces sont acceptés donc on les enlève avant de compter
    if (champTel) {
        var telSansEspaces = champTel.value.replaceAll(" ", "");
        if (telSansEspaces.length !== 10 || isNaN(telSansEspaces)) {
            document.getElementById("erreur-telephone").innerHTML = "Le numéro doit contenir exactement 10 chiffres.";
            valide = false;
        }
    }

    // vérif email basique : faut au moins un @ et un point
    if (champEmail && (champEmail.value.indexOf("@") === -1 || champEmail.value.indexOf(".") === -1)) {
        document.getElementById("erreur-email").innerHTML = "Veuillez saisir une adresse e-mail valide.";
        valide = false;
    }

    // mot de passe minimum 8 caractères
    if (champMdp && champMdp.value.length < 8) {
        document.getElementById("erreur-mdp").innerHTML = "Le mot de passe doit contenir au moins 8 caractères.";
        valide = false;
    }

    // vérification de l'âge : il faut avoir au moins 18 ans
    if (champDate && champDate.value !== "") {
        var dateSaisie = new Date(champDate.value);
        var aujourdhui = new Date();

        var age = aujourdhui.getFullYear() - dateSaisie.getFullYear();

        // si l'anniversaire n'est pas encore passé cette année, on enlève 1
        if (aujourdhui.getMonth() < dateSaisie.getMonth() || (aujourdhui.getMonth() === dateSaisie.getMonth() && aujourdhui.getDate() < dateSaisie.getDate())) {
            age = age - 1;
        }

        if (age < 18) {
            document.getElementById("erreur-date").innerHTML = "Vous devez avoir au moins 18 ans pour commander.";
            valide = false;
        }
    }

    // la case "j'ai un estomac solide" est obligatoire
    if (champCertif && champCertif.checked === false) {
        document.getElementById("erreur-certification").innerHTML = "Vous devez certifier avoir un estomac solide.";
        valide = false;
    }

    // si un champ est invalide, on bloque l'envoi du formulaire
    if (valide === false) {
        event.preventDefault();
    }

    return valide;
}


// validation du formulaire de connexion
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


// barre de recherche sur l'accueil : évite d'envoyer une recherche vide
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


// vérifie que le commentaire fait moins de 250 caractères avant d'envoyer l'avis
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


// si le client choisit "pour plus tard", la date devient obligatoire
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


// vérifie que la quantité est entre 1 et 10 avant d'ajouter au panier
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


// le livreur valide ou abandonne une livraison
// une confirmation est demandée car l'action est irréversible
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
                // les boutons disparaissent une fois que c'est validé
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


// compteurs de caractères en temps réel
// fonctionne sur tous les champs qui ont un attribut data-compteur="id_du_span"
function initCompteursCaracteres() {
    var champs = document.querySelectorAll("[data-compteur]");

    for (var i = 0; i < champs.length; i = i + 1) {
        var champ = champs[i];
        var idCompteur = champ.getAttribute("data-compteur");
        var compteur = document.getElementById(idCompteur);

        if (compteur) {
            // la closure isole bien les variables champ et compteur pour chaque itération
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


// modification d'un champ du profil directement sur la page
// premier clic sur le crayon = on active le champ, deuxième clic = on envoie la modif au serveur
function modifierChampProfil(bouton) {
    var champ = bouton.parentNode.querySelector(".input-form, .textarea-form");
    var nomChamp = champ.getAttribute("data-champ");

    if (!champ || !nomChamp) {
        return;
    }

    if (champ.hasAttribute("readonly") || champ.hasAttribute("disabled")) {
        // le champ est en lecture seule donc on passe en mode édition
        champ.removeAttribute("readonly");
        champ.removeAttribute("disabled");
        champ.focus();
        bouton.innerHTML = "✅";
        bouton.classList.add("btn-edit-valider");
    } else {
        // le champ est éditable donc on envoie la nouvelle valeur au serveur
        var nouvelleValeur = champ.value;

        // les préférences de contact sont des checkboxes, pas un input texte
        if (nomChamp === "preferences_contact") {
            var checkboxes = bouton.parentNode.querySelectorAll("input[type='checkbox']");
            var liste = [];
            for (var i = 0; i < checkboxes.length; i = i + 1) {
                if (checkboxes[i].checked) {
                    liste.push(checkboxes[i].value);
                }
            }
            nouvelleValeur = liste.join(",");

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

// cas particulier pour les checkboxes des préférences de contact
// le premier clic active les cases, le deuxième déclenche la sauvegarde
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


// filtre les plats par catégorie, allergène et recherche textuelle
// la requête part au serveur qui renvoie le HTML des plats correspondants
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
            // on remplace le contenu de la zone sans recharger la page
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

// le tri se fait en js directement sur les éléments déjà affichés
// pas besoin de retourner au serveur pour ça
function trierPlats(critere) {
    var grilles = document.querySelectorAll(".plats-populaires");

    for (var g = 0; g < grilles.length; g = g + 1) {
        var grille = grilles[g];
        var plats = grille.querySelectorAll(".plat");

        // on convertit en tableau pour pouvoir utiliser sort()
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

        // on réinsère les éléments dans le bon ordre
        for (var j = 0; j < tableauPlats.length; j = j + 1) {
            grille.appendChild(tableauPlats[j]);
        }
    }
}


// modifie la quantité d'un article dans le panier (+1 ou -1)
// si la quantité tombe à 0 le serveur supprime l'article automatiquement
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
                // un rechargement suffit pour recalculer les totaux
                window.location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(function () {
            alert("Erreur réseau, veuillez réessayer.");
        });
}

// supprime complètement un article du panier après confirmation
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


// change le statut d'une commande depuis la page cuisine
// la ligne disparaît du tableau actuel une fois le changement validé
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
                // on retire la ligne du tableau sans recharger la page
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


// sauvegarde le statut et le livreur depuis la page détail d'une commande
// évite d'envoyer en livraison sans avoir assigné un livreur
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

    // si on passe en livraison, il faut un livreur
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


// bloque ou débloque un utilisateur depuis le tableau admin
// une confirmation est demandée car ça coupe la session de l'utilisateur immédiatement
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
                // on met à jour le bouton et la cellule état sans recharger
                if (actionActuelle === "bloquer") {
                    bouton.innerHTML = "DÉBLOQUER";
                    bouton.setAttribute("data-action", "debloquer");
                    bouton.classList.remove("btn-bloquer");
                    bouton.classList.add("btn-debloquer");
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


// fait passer un utilisateur au statut fidélité suivant (cycle dans la liste)
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


// ajoute ou retire des points fidélité à un utilisateur
// la valeur peut être négative pour retirer des points
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


// vérifie toutes les 30 secondes si le compte a été bloqué par l'admin
// si oui, l'utilisateur est déconnecté immédiatement
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
            // si le réseau coupe on ne fait rien, ça réessayera au prochain interval
        });
}


// initialisation au chargement de la page
window.addEventListener("load", function () {
    initCompteursCaracteres();

    // le polling de blocage ne démarre que si quelqu'un est connecté
    if (document.body.getAttribute("data-connecte") === "1") {
        setInterval(verifierBlocage, 30000);
    }
});
