<div class="list-container">
    <h2>🔧 Liste des Maintenances</h2>
    
    <?php if (!empty($maintenances)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Véhicule</th>
                    <th>Type d'Intervention</th>
                    <th>Date Intervention</th>
                    <th>Description</th>
                    <th>Coût</th>
                    <th>Odometer (Km)</th>
                    <th>Km Prochaine Échéance</th>
                    <th>Date Prochaine Échéance</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($maintenances as $maintenance): ?>
                    <tr>
                        <td><?= htmlspecialchars($maintenance->getId()) ?></td>
                        <td><?= htmlspecialchars($maintenance->getIdVehicule()) ?></td>
                        <td><?= htmlspecialchars($maintenance->getTypeIntervention()) ?></td>
                        <td><?= htmlspecialchars($maintenance->getDateIntervention()) ?></td>
                        <td><?= htmlspecialchars($maintenance->getDescription()) ?></td>
                        <td><?= number_format($maintenance->getCout(), 2) ?> €</td>
                        <td><?= number_format($maintenance->getOdometerKm(), 0) ?></td>
                        <td><?= number_format($maintenance->getKmProchayneEcheance(), 0) ?></td>
                        <td><?= htmlspecialchars($maintenance->getDateProchayneEcheance()) ?></td>
                        <td><span class="badge badge-<?= strtolower($maintenance->getStatut()) ?>"><?= htmlspecialchars($maintenance->getStatut()) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-message">Aucune maintenance trouvée.</p>
    <?php endif; ?>
</div>
