<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?? "Gestion de Flotte" ?></title>
    <style type="text/css">
        @import url("./css/base.css");
        @import url("./css/form.css");
        @import url("./css/cgu.css");
        @import url("./css/corps.css");
    </style>
</head>
<body>
    
    <header class="top-navbar">
        <div class="logo">
            <span class="logo-text">FlotteTracker Pro</span>
        </div>
        
        <nav class="nav-links">
            <a href="./?action=tableaubord" class="<?= (($action ?? 'defaut') === 'tableaubord' || ($action ?? 'defaut') === 'defaut' || ($action ?? 'defaut') === 'accueil') ? 'active' : '' ?>">📊 Tableau de Bord</a>
            <a href="./?action=carte" class="<?= (($action ?? 'defaut') === 'carte' || ($action ?? 'defaut') === 'flotte') ? 'active' : '' ?>">🗺️ Carte & Suivi GPS</a>
            <a href="./?action=vehicules" class="<?= (($action ?? 'defaut') === 'vehicules') ? 'active' : '' ?>">🚚 Véhicules</a>
            <a href="./?action=trajets" class="<?= (($action ?? 'defaut') === 'trajets') ? 'active' : '' ?>">🛣️ Trajets</a>
            <a href="./?action=depots" class="<?= (($action ?? 'defaut') === 'depots') ? 'active' : '' ?>">🏢 Dépôts</a>
            <a href="./?action=maintenances" class="<?= (($action ?? 'defaut') === 'maintenances') ? 'active' : '' ?>">🔧 Maintenances</a>
        </nav>
        
        <div class="user-profile">
            👤 Service Client
        </div>
    </header>

    <div id="corps">