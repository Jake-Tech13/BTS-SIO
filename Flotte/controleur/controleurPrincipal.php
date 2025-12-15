<?php
function controleurPrincipal($action){
    $lesActions = [];
    // Par défaut, on va sur le tableau de bord
    $lesActions["defaut"] = "controleurTableauBord.php";
    
    // Actions principales
    $lesActions["accueil"] = "controleurTableauBord.php";
    $lesActions["livraisons"] = "listeLivraisons.php"; // À créer plus tard
    $lesActions["flotte"] = "listeFlotte.php";         // À créer plus tard
    
    // Authentification
    $lesActions["connexion"] = "connexion.php"; // Attention: connexion est à la racine, à adapter si besoin
    $lesActions["deconnexion"] = "deconnexion.php";

    if (array_key_exists ( $action , $lesActions )){
        return $lesActions[$action];
    }
    else{
        return $lesActions["defaut"];
    }
}
