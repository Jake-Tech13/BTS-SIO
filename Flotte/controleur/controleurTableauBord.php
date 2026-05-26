<?php
if ($_SERVER["SCRIPT_FILENAME"] == __FILE__) {
    $racine = "..";
} else {
    if (!isset($racine)) {
        $racine = ".";
    }
}

include_once "$racine/modele/bd.inc.php";
include_once "$racine/core/DAO/VehiculeDAO.php";
include_once "$racine/core/DAO/LivraisonDAO.php";
include_once "$racine/core/DAO/FactureDAO.php";
include_once "$racine/core/DAO/MaintenanceDAO.php";
include_once "$racine/core/DAO/DepotDAO.php";
include_once "$racine/core/DAO/ChauffeurDAO.php";
include_once "$racine/core/DAO/ClientDAO.php";
include_once "$racine/core/DAO/TrajetDAO.php";
include_once "$racine/core/DAO/GpsDAO.php";

$bd = Connexion::connexionPDO();

// ========== STATISTIQUES GLOBALES ==========
$vehiculeDAO = new VehiculeDAO();
$livraisonDAO = new LivraisonDAO();
$factureDAO = new FactureDAO();
$maintenanceDAO = new MaintenanceDAO();
$depotDAO = new DepotDAO();
$chauffeurDAO = new ChauffeurDAO();
$clientDAO = new ClientDAO();

// Véhicules par statut
$vehiculesDisponibles = $vehiculeDAO->getByStatut('disponible');
$vehiculesEnService = $vehiculeDAO->getByStatut('en_service');
$vehiculesEnEntretien = $vehiculeDAO->getByStatut('en_entretien');
$vehiculesHorsService = $vehiculeDAO->getByStatut('hors_service');

$statsVehicules = [
    'total' => count($vehiculesDisponibles) + count($vehiculesEnService) + count($vehiculesEnEntretien) + count($vehiculesHorsService),
    'disponible' => count($vehiculesDisponibles),
    'en_service' => count($vehiculesEnService),
    'en_entretien' => count($vehiculesEnEntretien),
    'hors_service' => count($vehiculesHorsService)
];

// Livraisons par statut
$livraisonsEnCours = $livraisonDAO->getByStatut('en_cours');
$livraisonsLivrees = $livraisonDAO->getByStatut('livree');
$livraisonsPrevues = $livraisonDAO->getByStatut('prevue');
$livraisonsAnnulees = $livraisonDAO->getByStatut('annulee');

$statsLivraisons = [
    'total' => count($livraisonsEnCours) + count($livraisonsLivrees) + count($livraisonsPrevues) + count($livraisonsAnnulees),
    'en_cours' => count($livraisonsEnCours),
    'livree' => count($livraisonsLivrees),
    'prevue' => count($livraisonsPrevues),
    'annulee' => count($livraisonsAnnulees)
];

// Factures par statut
$facturesEmises = $factureDAO->getByStatut('emise');
$facturesPay = $factureDAO->getByStatut('payee');
$facturesImpay = $factureDAO->getByStatut('impayee');

$statsFactures = [
    'emise' => count($facturesEmises),
    'payee' => count($facturesPay),
    'impayee' => count($facturesImpay),
    'montant_total_ht' => $factureDAO->getChiffreAffaires(date('Y-m-d', strtotime('-30 days')), date('Y-m-d'))
];

// Maintenances dues
$maintenancesDues = $maintenanceDAO->getByStatut('prevue');
$statsMaintenances = [
    'dues' => count($maintenancesDues)
];

// ========== LIVRAISONS DÉTAILLÉES EN COURS ==========
$livraisonsDetailees = [];
foreach ($livraisonsEnCours as $livraison) {
    $client = $clientDAO->getById($livraison->getIdClient());
    $livraisonsDetailees[] = [
        'livraison' => $livraison,
        'client' => $client
    ];
}

// ========== VÉHICULES EN SERVICE SUR CARTE ==========
$listeVehiculesSurCarte = [];
foreach ($vehiculesEnService as $vehicule) {
    // Chercher le trajet actif
    $sql = "SELECT id_gps FROM trajet WHERE id_vehicule = :idV AND statut = 'en_cours'";
    $stmt = $bd->prepare($sql);
    $stmt->execute([':idV' => $vehicule->getId()]);
    $trajetData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($trajetData) {
        // Récupérer la position GPS
        $sqlGps = "SELECT * FROM gps WHERE id_position = :idG";
        $stmtGps = $bd->prepare($sqlGps);
        $stmtGps->execute([':idG' => $trajetData['id_gps']]);
        $gpsData = $stmtGps->fetch(PDO::FETCH_ASSOC);

        if ($gpsData) {
            $listeVehiculesSurCarte[] = [
                'id_vehicule' => $vehicule->getId(),
                'immatriculation' => $vehicule->getImmatriculation(),
                'modele' => $vehicule->getModele(),
                'latitude' => $gpsData['latitude'],
                'longitude' => $gpsData['longitude'],
                'vitesse_kmh' => $gpsData['vitesse_kmh'],
                'horodatage' => $gpsData['horodatage']
            ];
        }
    }
}

// ========== DÉPÔTS ==========
$sql = "SELECT * FROM depot";
$req = $bd->query($sql);
$listeDepots = [];
while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
    $listeDepots[] = $row;
}

$titre = "Tableau de Bord - Suivi Flotte";
$selectedCategory = "Tableau de Bord";

// Inclure la vue avec toutes les variables préparées
include_once "$racine/vue/vueTableauBord.php";
