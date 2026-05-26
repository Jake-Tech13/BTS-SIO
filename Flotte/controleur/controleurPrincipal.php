<?php
function controleurPrincipal($action){
    $lesActions = [];
    // Par défaut, on va sur le tableau de bord
    $lesActions["defaut"] = "controleur/controleurTableauBord.php";
    
    // Actions principales
    $lesActions["accueil"] = "controleur/controleurTableauBord.php";
    $lesActions["livraisons"] = "controleur/listeLivraisons.php"; // À créer plus tard
    $lesActions["flotte"] = "controleur/listeFlotte.php";         // À créer plus tard
    
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
