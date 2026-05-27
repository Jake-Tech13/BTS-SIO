<div class="list-container">
    <h2>🛣️ Liste des Trajets</h2>
    
    <?php if (!empty($trajets)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Véhicule</th>
                    <th>ID Chauffeur</th>
                    <th>Départ Prévu</th>
                    <th>Départ Réel</th>
                    <th>Arrivée Prévue</th>
                    <th>Arrivée Réelle</th>
                    <th>Statut</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trajets as $trajet): ?>
                    <tr>
                        <td><?= htmlspecialchars($trajet->getId()) ?></td>
                        <td><?= htmlspecialchars($trajet->getIdVehicule()) ?></td>
                        <td><?= htmlspecialchars($trajet->getIdChauffeur()) ?></td>
                        <td><?= htmlspecialchars($trajet->getHeureDepartPrevue()) ?></td>
                        <td><?= htmlspecialchars($trajet->getHeureDepartReelle()) ?></td>
                        <td><?= htmlspecialchars($trajet->getHeureArrivePrevue()) ?></td>
                        <td><?= htmlspecialchars($trajet->getHeureArriveReelle()) ?></td>
                        <td><span class="badge badge-<?= strtolower($trajet->getStatut()) ?>"><?= htmlspecialchars($trajet->getStatut()) ?></span></td>
                        <td><?= htmlspecialchars($trajet->getNotes()) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-message">Aucun trajet trouvé.</p>
    <?php endif; ?>
</div>
