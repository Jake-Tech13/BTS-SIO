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

$bd = Connexion::connexionPDO();

// ========== INSTANCIATION DES DAO ==========
$vehiculeDAO = new VehiculeDAO();
$livraisonDAO = new LivraisonDAO();
$factureDAO = new FactureDAO();
$maintenanceDAO = new MaintenanceDAO();
$depotDAO = new DepotDAO();
$chauffeurDAO = new ChauffeurDAO();
$clientDAO = new ClientDAO();

// ========== STATISTIQUES VÉHICULES ==========
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

// ========== STATISTIQUES LIVRAISONS ==========
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

// ========== STATISTIQUES FACTURES ==========
$facturesEmises = $factureDAO->getByStatut('emise');
$facturesPayees = $factureDAO->getByStatut('payee');
$facturesImpayees = $factureDAO->getByStatut('impayee');

$statsFactures = [
    'emise' => count($facturesEmises),
    'payee' => count($facturesPayees),
    'impayee' => count($facturesImpayees),
    'montant_total_ht' => $factureDAO->getChiffreAffaires(date('Y-m-d', strtotime('-30 days')), date('Y-m-d'))
];

// ========== LIVRAISONS DÉTAILLÉES (POUR LE TABLEAU) ==========
$livraisonsDetailees = [];
foreach ($livraisonsEnCours as $livraison) {
    $client = $clientDAO->getById($livraison->getIdClient());
    $livraisonsDetailees[] = [
        'livraison' => $livraison,
        'client' => $client
    ];
}

// ========== MAINTENANCES DUES ==========
$maintenancesDues = $maintenanceDAO->getByStatut('prevue');
$maintenancesDetailees = [];
foreach ($maintenancesDues as $maintenance) {
    $vehicule = $vehiculeDAO->getById($maintenance->getIdVehicule());
    $maintenancesDetailees[] = [
        'maintenance' => $maintenance,
        'immatriculation' => $vehicule ? $vehicule->getImmatriculation() : 'N/A'
    ];
}
$statsMaintenances = [
    'dues' => count($maintenancesDues)
];

// ========== LISTE DES DÉPÔTS ==========
$depotsObjets = $depotDAO->getAll();
$listeDepots = [];
foreach ($depotsObjets as $d) {
    // On utilise la méthode native de l'objet pour le transformer en tableau
    $listeDepots[] = $d->toArray(); 
}

// Variables pour l'entête
$titre = "Tableau de Bord - Synthèse";
$selectedCategory = "Tableau de Bord";

// Appel de la vue
include_once "$racine/vue/vueTableauBord.php";
