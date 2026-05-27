<div class="list-container">
    <h2>🚚 Liste des Véhicules</h2>
    
    <?php if (!empty($vehicules)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Immatriculation</th>
                    <th>Modèle</th>
                    <th>Année</th>
                    <th>Capacité (Kg)</th>
                    <th>Capacité (M³)</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicules as $vehicule): ?>
                    <tr>
                        <td><?= htmlspecialchars($vehicule->getId()) ?></td>
                        <td><strong><?= htmlspecialchars($vehicule->getImmatriculation()) ?></strong></td>
                        <td><?= htmlspecialchars($vehicule->getModele()) ?></td>
                        <td><?= htmlspecialchars($vehicule->getAnnee()) ?></td>
                        <td><?= number_format($vehicule->getCapaciteKg(), 0) ?> kg</td>
                        <td><?= number_format($vehicule->getCapaciteM3(), 2) ?> m³</td>
                        <td><span class="badge badge-<?= strtolower($vehicule->getStatut()) ?>"><?= htmlspecialchars($vehicule->getStatut()) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-message">Aucun véhicule trouvé.</p>
    <?php endif; ?>
</div>
