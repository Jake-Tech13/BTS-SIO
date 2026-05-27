<?php
function controleurPrincipal($action){
    $lesActions = [];
    // Par défaut, on va sur le tableau de bord
    $lesActions["defaut"] = "controleur/controleurTableauBord.php";
    
    // Actions principales
    $lesActions["accueil"] = "controleur/controleurTableauBord.php";
    $lesActions["tableaubord"] = "controleur/controleurTableauBord.php"; // Alias
    $lesActions["flotte"] = "controleur/controleurCarte.php";
    $lesActions["carte"] = "controleur/controleurCarte.php"; // Alias
    $lesActions["livraisons"] = "controleur/listeLivraisons.php"; // À créer plus tard
    
    // Authentification
    $lesActions["connexion"] = "connexion.php"; // À la racine
    $lesActions["deconnexion"] = "deconnexion.php"; // À la racine

    if (array_key_exists ( $action , $lesActions )){
        return $lesActions[$action];
    }
    else{
        return $lesActions["defaut"];
    }
}
