<?php
// Inclusion des DAOs
include_once './core/DAO/VehiculeDAO.php';

$vehiculeDAO = new VehiculeDAO();
$vehicules = $vehiculeDAO->getAll();

$titre = "Gestion des Véhicules";
$selectedCategory = "Véhicules";

// Inclure la vue
include_once './vue/vueVehicules.php';
