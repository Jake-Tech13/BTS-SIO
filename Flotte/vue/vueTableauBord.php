<link rel="stylesheet" href="./css/tableaubord.css">

<div class="erp-dashboard">
    <div class="erp-header">
        <div>
            <h1 class="erp-title">Synthèse globale</h1>
            <span class="erp-subtitle">Vue d'ensemble de l'activité logistique</span>
        </div>
        <div class="erp-timestamp">
            Dernière actualisation : <strong><?= date('d/m/Y H:i') ?></strong>
        </div>
    </div>

    <div class="erp-kpi-grid">
        <div class="erp-card kpi-card border-top-primary">
            <div class="kpi-title">Parc Véhicules</div>
            <div class="kpi-value"><?= $statsVehicules['total'] ?></div>
            <div class="kpi-details">
                <span class="badge badge-success"><?= $statsVehicules['disponible'] ?> Dispo</span>
                <span class="badge badge-warning"><?= $statsVehicules['en_service'] ?> En service</span>
                <span class="badge badge-danger"><?= $statsVehicules['en_entretien'] ?> Entretien</span>
            </div>
        </div>

        <div class="erp-card kpi-card border-top-info">
            <div class="kpi-title">Livraisons Actives</div>
            <div class="kpi-value"><?= $statsLivraisons['en_cours'] + $statsLivraisons['prevue'] ?></div>
            <div class="kpi-details">
                <span class="text-muted"><?= $statsLivraisons['en_cours'] ?> en transit, <?= $statsLivraisons['prevue'] ?> en attente</span>
            </div>
        </div>

        <div class="erp-card kpi-card border-top-danger">
            <div class="kpi-title">Alertes Maintenance</div>
            <div class="kpi-value text-danger"><?= $statsMaintenances['dues'] ?></div>
            <div class="kpi-details">
                <span class="text-muted">Interventions programmées requises</span>
            </div>
        </div>

        <div class="erp-card kpi-card border-top-success">
            <div class="kpi-title">CA (30 derniers jours)</div>
            <div class="kpi-value"><?= number_format($statsFactures['montant_total_ht'], 0, ',', ' ') ?> €</div>
            <div class="kpi-details">
                <span class="text-muted"><?= $statsFactures['impayee'] ?> facture(s) en attente de paiement</span>
            </div>
        </div>
    </div>

    <div class="erp-main-grid">
        
        <div class="erp-col-left">
            <div class="erp-card">
                <div class="erp-card-header">
                    <h2>Livraisons en cours de transit</h2>
                </div>
                <div class="erp-card-body p-0">
                    <?php if (empty($livraisonsDetailees)): ?>
                        <div class="erp-empty-state">Aucune livraison en transit actuellement.</div>
                    <?php else: ?>
                        <table class="erp-table">
                            <thead>
                                <tr>
                                    <th>Réf. Commande</th>
                                    <th>Client (Raison Sociale)</th>
                                    <th>Volume / Poids</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($livraisonsDetailees as $item): 
                                    $livraison = $item['livraison'];
                                    $client = $item['client'];
                                ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($livraison->getReference()) ?></strong></td>
                                        <td><?= htmlspecialchars($client ? $client->getRaisonSociale() : 'Non assigné') ?></td>
                                        <td class="text-muted"><?= number_format($livraison->getVolumeM3(), 2) ?> m³ / <?= number_format($livraison->getPoidsKg(), 0) ?> kg</td>
                                        <td><span class="badge badge-warning">En transit</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="erp-card mt-3">
                <div class="erp-card-header">
                    <h2>Dépôts Logistiques</h2>
                </div>
                <div class="erp-card-body p-0">
                    <table class="erp-table">
                        <thead>
                            <tr>
                                <th>Nom du dépôt</th>
                                <th>Localisation</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listeDepots as $depot): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($depot['nom']) ?></strong></td>
                                    <td><?= htmlspecialchars($depot['ville']) ?> (<?= htmlspecialchars($depot['code_postal']) ?>)</td>
                                    <td class="text-muted"><?= htmlspecialchars($depot['tel'] ?? 'N/C') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="erp-col-right">
            <div class="erp-card">
                <div class="erp-card-header bg-light-danger">
                    <h2 class="text-danger">Interventions requises</h2>
                </div>
                <div class="erp-card-body">
                    <?php if (empty($maintenancesDetailees)): ?>
                        <div class="erp-empty-state text-success">Aucune maintenance en souffrance.</div>
                    <?php else: ?>
                        <ul class="erp-list">
                            <?php foreach ($maintenancesDetailees as $m): ?>
                                <li>
                                    <div class="list-title"><?= htmlspecialchars($m['immatriculation']) ?></div>
                                    <div class="list-desc"><?= htmlspecialchars($m['maintenance']->getTypeIntervention()) ?></div>
                                    <div class="list-meta text-danger">Prévue le : <?= date('d/m/Y', strtotime($m['maintenance']->getDateIntervention())) ?></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <div class="erp-card mt-3">
                <div class="erp-card-header">
                    <h2>Véhicules Actifs</h2>
                </div>
                <div class="erp-card-body p-0">
                    <?php if (empty($vehiculesEnService)): ?>
                         <div class="erp-empty-state">Tous les véhicules sont au dépôt.</div>
                    <?php else: ?>
                        <ul class="erp-list compact">
                            <?php foreach ($vehiculesEnService as $vehicule): ?>
                                <li>
                                    <div class="list-title"><?= htmlspecialchars($vehicule->getImmatriculation()) ?></div>
                                    <div class="list-desc"><?= htmlspecialchars($vehicule->getModele()) ?></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>