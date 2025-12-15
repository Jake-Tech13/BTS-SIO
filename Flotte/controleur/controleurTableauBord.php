<?php
if ( $_SERVER["SCRIPT_FILENAME"] == __FILE__ ){
    $racine="..";
}

include_once "$racine/core/DAO/VehiculeDAO.php";
include_once "$racine/core/DAO/DepotDAO.php";
include_once "$racine/core/DAO/GpsDAO.php";
include_once "$racine/core/DAO/TrajetDAO.php";

// 1. Récupération des dépôts (pour les afficher en bleu sur la carte)
$depotDAO = new DepotDAO();
// Note: J'utilise une méthode fictive getAll() ou rechercherParVille("") pour tout avoir
// Si getAll() n'existe pas, utilise rechercherParVille("") avec une chaine vide ou adapte ton DAO.
$listeDepots = $depotDAO->rechercherParVille(""); 

// 2. Récupération des véhicules en mouvement (pour les afficher en rouge/camion)
// Pour simplifier, on récupère tous les véhicules et on regardera s'ils ont un trajet actif
$vehiculeDAO = new VehiculeDAO();
$trajetDAO = new TrajetDAO();
$gpsDAO = new GpsDAO();

$listeVehiculesSurCarte = [];

// On récupère les véhicules "en_service"
$req = Connexion::connexionPDO()->query("SELECT * FROM vehicule WHERE statut = 'en_service'");
while($row = $req->fetch(PDO::FETCH_ASSOC)) {
    // Pour chaque véhicule, on cherche sa dernière position GPS
    // Astuce : On suppose ici que la table GPS est liée au véhicule ou au trajet.
    // Comme ton schéma lie Trajet -> GPS, on cherche le trajet en cours.
    
    $sqlTrajet = "SELECT id_gps FROM trajet WHERE id_vehicule = :idV AND statut = 'en_cours'";
    $stmtT = Connexion::connexionPDO()->prepare($sqlTrajet);
    $stmtT->execute([':idV' => $row['id_vehicule']]);
    $trajetData = $stmtT->fetch(PDO::FETCH_ASSOC);

    if ($trajetData) {
        // On a un trajet, on cherche le GPS
        $gps = $gpsDAO->getDernierePos($row['id_vehicule']); // Utilise ta méthode DAO ou fetch direct
        
        // Si ta méthode getDernierePosition n'est pas encore parfaite, voici un hack SQL direct pour le test :
        $sqlGps = "SELECT * FROM gps WHERE id_position = :idG";
        $stmtG = Connexion::connexionPDO()->prepare($sqlGps);
        $stmtG->execute([':idG' => $trajetData['id_gps']]);
        $gpsData = $stmtG->fetch(PDO::FETCH_ASSOC);

        if ($gpsData) {
            $listeVehiculesSurCarte[] = [
                'immat' => $row['immatriculation'],
                'modele' => $row['modele'],
                'lat' => $gpsData['latitude'],
                'lon' => $gpsData['longitude'],
                'vitesse' => $gpsData['vitesse_kmh']
            ];
        }
    }
}

$titre = "Tableau de Bord - Suivi Flotte";
include "$racine/vue/entete.html.php";
include "$racine/vue/vueTableauBord.php";
include "$racine/vue/pied.html.php";
