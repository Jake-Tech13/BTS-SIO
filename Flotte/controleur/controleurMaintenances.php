<?php
// Inclusion des DAOs
include_once './core/DAO/MaintenanceDAO.php';

$maintenanceDAO = new MaintenanceDAO();
$maintenances = $maintenanceDAO->getAll();

$titre = "Gestion des Maintenances";
$selectedCategory = "Maintenances";

// Inclure la vue
include_once './vue/vueMaintenances.php';
