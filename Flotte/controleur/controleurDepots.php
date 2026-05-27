<?php
// Inclusion des DAOs
include_once './core/DAO/DepotDAO.php';

$depotDAO = new DepotDAO();
$depots = $depotDAO->getAll();

$titre = "Gestion des Dépôts";
$selectedCategory = "Dépôts";

// Inclure la vue
include_once './vue/vueDepots.php';
