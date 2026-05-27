<?php
if ($_SERVER["SCRIPT_FILENAME"] == __FILE__) {
    $racine = "..";
} else {
    if (!isset($racine)) {
        $racine = ".";
    }
}

include_once "$racine/modele/bd.inc.php";
include_once "$racine/core/DAO/DepotDAO.php";

$bd = Connexion::connexionPDO();
$depotDAO = new DepotDAO();

// Récupérer tous les dépôts
$depotsObjets = $depotDAO->getAll();

// Préparer les données pour la carte en JSON
$depotsData = [];
foreach ($depotsObjets as $depot) {
    // On ne garde que les dépôts qui ont des coordonnées valides
    if ($depot->estAccessible()) {
        $depotsData[] = [
            'nom' => $depot->getNom(),
            'ville' => $depot->getVille(),
            'lat' => $depot->getLatitude(),
            'lng' => $depot->getLongitude(),
            'tel' => $depot->getTelephone()
        ];
    }
}
$depotsJson = json_encode($depotsData);

$titre = "Carte & Suivi GPS";
$selectedCategory = "Carte & Suivi";

include "$racine/vue/entete.html.php";
include "$racine/vue/vueCarte.php";
include "$racine/vue/pied.html.php";
?>