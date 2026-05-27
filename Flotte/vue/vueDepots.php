<div class="list-container">
    <h2>🏢 Liste des Dépôts</h2>
    
    <?php if (!empty($depots)): ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Contact</th>
                    <th>Adresse</th>
                    <th>Ville</th>
                    <th>Téléphone</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($depots as $depot): ?>
                    <tr>
                        <td><?= htmlspecialchars($depot->getId()) ?></td>
                        <td><strong><?= htmlspecialchars($depot->getNom()) ?></strong></td>
                        <td><?= htmlspecialchars($depot->getNomContact()) ?></td>
                        <td><?= htmlspecialchars($depot->getAdresse()) ?></td>
                        <td><?= htmlspecialchars($depot->getVille()) ?></td>
                        <td><?= htmlspecialchars($depot->getTelephone()) ?></td>
                        <td><?= number_format($depot->getLatitude(), 6) ?></td>
                        <td><?= number_format($depot->getLongitude(), 6) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-message">Aucun dépôt trouvé.</p>
    <?php endif; ?>
</div>
