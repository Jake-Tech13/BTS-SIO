<?php
session_start();

// Inclusion du contrôleur principal
include_once 'controleur/controleurPrincipal.php';

// Gestion du routing
$action = isset($_GET['action']) ? $_GET['action'] : 'defaut';

// Sécurité : vérifier que l'utilisateur est connecté (sauf pour les actions publiques)
$actionsPubliques = ['connexion', 'inscription', 'deconnexion'];
if (!isset($_SESSION['id_user']) && !in_array($action, $actionsPubliques)) {
    header("Location: connexion.php");
    exit;
}

// Récupérer le fichier à inclure
$fichier = controleurPrincipal($action);

// ========== INITIALISER LES VARIABLES PAR DÉFAUT ==========
$titre = "Flotte - Gestion de Transport";
$selectedCategory = "Tableau de Bord";

// Statistiques par défaut
$statsVehicules = ['total' => 0, 'disponible' => 0, 'en_service' => 0, 'en_entretien' => 0, 'hors_service' => 0];
$statsLivraisons = ['total' => 0, 'en_cours' => 0, 'livree' => 0, 'prevue' => 0, 'annulee' => 0];
$statsFactures = ['emise' => 0, 'payee' => 0, 'impayee' => 0, 'montant_total_ht' => 0];
$statsMaintenances = ['dues' => 0];

// Listes par défaut
$livraisonsDetailees = [];
$vehiculesEnService = [];
$listeVehiculesSurCarte = [];
$listeDepots = [];
$maintenancesDues = [];
$vehiculeDAO = null;

// Inclure l'entête (variables maintenant définies)
include_once 'vue/entete.html.php';

// Inclure le contrôleur approprié (qui peut modifier les variables)
$racine = ".";
include_once $fichier;

// Inclure le pied de page
include_once 'vue/pied.html.php';
