        <h1>Tableau de Bord - Suivi Flotte</h1>
        <!-- Contenu du tableau de bord, par exemple carte ou liste -->
        <p>Contenu du tableau de bord ici.</p>
        <!-- Afficher les véhicules et dépôts -->
        <h2>Véhicules en service :</h2>
        <ul>
            <?php foreach ($listeVehiculesSurCarte as $vehicule) { ?>
                <li><?php echo $vehicule['immat'] . ' - ' . $vehicule['modele'] . ' à (' . $vehicule['lat'] . ', ' . $vehicule['lon'] . ') vitesse: ' . $vehicule['vitesse']; ?></li>
            <?php } ?>
        </ul>
        <h2>Dépôts :</h2>
        <ul>
            <?php foreach ($listeDepots as $depot) { ?>
                <li><?php echo $depot->getNom(); ?> à <?php echo $depot->getVille(); ?></li>
            <?php } ?>
        </ul>