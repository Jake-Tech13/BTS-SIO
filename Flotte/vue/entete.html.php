<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title><?php echo $titre ?></title>
        <style type="text/css">
            @import url("../css/base.css");
            @import url("../css/form.css");
            @import url("../css/cgu.css");
            @import url("../css/corps.css");
        </style>
        <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
        <script>
            function toggleMenu() {
                const menu = document.getElementById('side-menu');
                menu.classList.toggle('open');
            }

            function selectCategory(name) {
                document.getElementById('category-name').textContent = name;
                toggleMenu(); // Fermer le menu après sélection
            }

            function toggleUserDropdown() {
                const dropdown = document.getElementById('user-dropdown');
                const arrow = document.getElementById('arrow');
                dropdown.classList.toggle('open');
                arrow.textContent = dropdown.classList.contains('open') ? 'v' : '^';
            }
        </script>
    </head>
    <body>
        <!-- Bannière fixe -->
        <div id="banner">
            <div id="hamburger" onclick="toggleMenu()">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div id="category-name"><?php echo isset($selectedCategory) ? $selectedCategory : 'Tableau de Bord'; ?></div>
            <div id="user-menu" onclick="toggleUserDropdown()">
                <img src="../images/profil.png" alt="user" />
                <span id="arrow">^</span>
                <ul id="user-dropdown">
                    <li><a href="#">Profil</a></li>
                    <li><a href="#">Paramètres</a></li>
                    <li><a href="#">Déconnexion</a></li>
                </ul>
            </div>
        </div>

        <!-- Menu latéral pour hamburger -->
        <div id="side-menu">
            <ul>
                <li onclick="selectCategory('Tableau de Bord')">Tableau de Bord</li>
                <li onclick="selectCategory('Véhicules')">Véhicules</li>
                <li onclick="selectCategory('Trajets')">Trajets</li>
                <li onclick="selectCategory('Dépôts')">Dépôts</li>
                <li onclick="selectCategory('Maintenance')">Maintenance</li>
            </ul>
        </div>

        <div id="corps">
        