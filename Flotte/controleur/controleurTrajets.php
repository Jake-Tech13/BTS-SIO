<?php
// Inclusion des DAOs
include_once './core/DAO/TrajetDAO.php';

$trajetDAO = new TrajetDAO();
$trajets = $trajetDAO->getAll();

$titre = "Gestion des Trajets";
$selectedCategory = "Trajets";

// Inclure la vue
include_once './vue/vueTrajets.php';
