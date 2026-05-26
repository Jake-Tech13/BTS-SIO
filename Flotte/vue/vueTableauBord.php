<!-- Tableau de Bord - Suivi Flotte -->
<div class="tableau-bord-container">
    <header class="tableau-bord-header">
        <h1>📊 Tableau de Bord - Suivi Flotte</h1>
        <p class="date-heure">Mise à jour : <?php echo date('d/m/Y H:i:s'); ?></p>
    </header>

    <!-- ========== STATISTIQUES GLOBALES ========== -->
    <section class="stats-grid">
        <div class="stat-card stat-vehicules">
            <div class="stat-icon">🚗</div>
            <div class="stat-content">
                <h3>Flotte Véhicules</h3>
                <div class="stat-value"><?php echo $statsVehicules['total']; ?></div>
                <div class="stat-details">
                    <span class="disponible">Dispo: <?php echo $statsVehicules['disponible']; ?></span>
                    <span class="en_service">Service: <?php echo $statsVehicules['en_service']; ?></span>
                    <span class="en_entretien">Entretien: <?php echo $statsVehicules['en_entretien']; ?></span>
                    <span class="hors_service">Hors service: <?php echo $statsVehicules['hors_service']; ?></span>
                </div>
            </div>
        </div>

        <div class="stat-card stat-livraisons">
            <div class="stat-icon">📦</div>
            <div class="stat-content">
                <h3>Livraisons</h3>
                <div class="stat-value"><?php echo $statsLivraisons['total']; ?></div>
                <div class="stat-details">
                    <span class="prevue">Prévues: <?php echo $statsLivraisons['prevue']; ?></span>
                    <span class="en_cours">En cours: <?php echo $statsLivraisons['en_cours']; ?></span>
                    <span class="livree">Livrées: <?php echo $statsLivraisons['livree']; ?></span>
                </div>
            </div>
        </div>

        <div class="stat-card stat-factures">
            <div class="stat-icon">💰</div>
            <div class="stat-content">
                <h3>Factures</h3>
                <div class="stat-value"><?php echo number_format($statsFactures['montant_total_ht'], 2); ?>€</div>
                <div class="stat-details">
                    <span class="emise">Émises: <?php echo $statsFactures['emise']; ?></span>
                    <span class="payee">Payées: <?php echo $statsFactures['payee']; ?></span>
                    <span class="impayee">Impayées: <?php echo $statsFactures['impayee']; ?></span>
                </div>
            </div>
        </div>

        <div class="stat-card stat-maintenance">
            <div class="stat-icon">🔧</div>
            <div class="stat-content">
                <h3>Maintenance</h3>
                <div class="stat-value"><?php echo $statsMaintenances['dues']; ?></div>
                <div class="stat-details">
                    <span>Interventions prévues</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== SECTION PRINCIPALE ========== -->
    <div class="main-content">
        <!-- COLONNE 1: LIVRAISONS EN COURS -->
        <section class="section-livraisons">
            <h2>📋 Livraisons en Cours</h2>
            <div class="livraisons-list">
                <?php if (empty($livraisonsDetailees)): ?>
                    <div class="empty-state">
                        <p>Aucune livraison en cours</p>
                    </div>
                <?php else: ?>
                    <table class="table-livraisons">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Client</th>
                                <th>Poids</th>
                                <th>Volume</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($livraisonsDetailees as $item): 
                                $livraison = $item['livraison'];
                                $client = $item['client'];
                                $statutClass = 'statut-' . $livraison->getStatut();
                            ?>
                                <tr class="livraison-row">
                                    <td class="ref"><strong><?php echo htmlspecialchars($livraison->getReference()); ?></strong></td>
                                    <td class="client"><?php echo htmlspecialchars($client ? $client->getRaisonSociale() : 'N/A'); ?></td>
                                    <td class="poids"><?php echo number_format($livraison->getPoidsKg(), 2); ?> kg</td>
                                    <td class="volume"><?php echo number_format($livraison->getVolumeM3(), 3); ?> m³</td>
                                    <td class="<?php echo $statutClass; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $livraison->getStatut())); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </section>

        <!-- COLONNE 2: VÉHICULES & CONTRÔLES -->
        <aside class="sidebar">
            <!-- VÉHICULES EN SERVICE -->
            <section class="section-sidebar">
                <h3>🚗 Véhicules en Service</h3>
                <div class="vehicles-list">
                    <?php if (empty($vehiculesEnService)): ?>
                        <p class="text-muted">Aucun véhicule en service</p>
                    <?php else: ?>
                        <?php foreach ($vehiculesEnService as $vehicule): ?>
                            <div class="vehicle-card">
                                <div class="vehicle-header">
                                    <strong><?php echo htmlspecialchars($vehicule->getImmatriculation()); ?></strong>
                                </div>
                                <div class="vehicle-details">
                                    <p><?php echo htmlspecialchars($vehicule->getModele()); ?></p>
                                    <p class="capacity">
                                        Capacité: <?php echo number_format($vehicule->getCapaciteKg(), 0); ?> kg
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <!-- DÉPÔTS -->
            <section class="section-sidebar">
                <h3>📍 Dépôts</h3>
                <div class="depots-list">
                    <?php if (empty($listeDepots)): ?>
                        <p class="text-muted">Aucun dépôt enregistré</p>
                    <?php else: ?>
                        <?php foreach ($listeDepots as $depot): ?>
                            <div class="depot-card">
                                <div class="depot-name">
                                    <strong><?php echo htmlspecialchars($depot['nom']); ?></strong>
                                </div>
                                <div class="depot-info">
                                    <p><?php echo htmlspecialchars($depot['ville']); ?></p>
                                    <p class="contact"><?php echo htmlspecialchars($depot['tel'] ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <!-- MAINTENANCES DUES -->
            <section class="section-sidebar alert-section">
                <h3>🔧 Maintenances Dues</h3>
                <div class="maintenances-list">
                    <?php if (empty($maintenancesDues)): ?>
                        <p class="text-success">✓ Pas de maintenance urgente</p>
                    <?php else: ?>
                        <?php foreach ($maintenancesDues as $maintenance): 
                            $vehicule = $vehiculeDAO->getById($maintenance->getIdVehicule());
                        ?>
                            <div class="maintenance-alert">
                                <div class="alert-title">
                                    <?php echo htmlspecialchars($vehicule ? $vehicule->getImmatriculation() : 'N/A'); ?>
                                </div>
                                <div class="alert-details">
                                    <p><?php echo htmlspecialchars($maintenance->getTypeIntervention()); ?></p>
                                    <p class="date">
                                        Prévue: <?php echo date('d/m/Y', strtotime($maintenance->getDateIntervention())); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </aside>
    </div>

    <!-- ========== SECTION CARTE GPS ========== -->
    <section class="section-carte">
        <h2>🗺️ Suivi GPS Temps Réel</h2>
        <div class="carte-container">
            <table class="table-gps">
                <thead>
                    <tr>
                        <th>Véhicule</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Vitesse</th>
                        <th>Mise à jour</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($listeVehiculesSurCarte)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucun véhicule actuellement suivi</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($listeVehiculesSurCarte as $vehicule): ?>
                            <tr class="gps-row">
                                <td class="vehicle-info">
                                    <strong><?php echo htmlspecialchars($vehicule['immatriculation']); ?></strong>
                                    <br><small><?php echo htmlspecialchars($vehicule['modele']); ?></small>
                                </td>
                                <td><?php echo number_format($vehicule['latitude'], 7); ?></td>
                                <td><?php echo number_format($vehicule['longitude'], 7); ?></td>
                                <td class="vitesse">
                                    <?php echo number_format($vehicule['vitesse_kmh'] ?? 0, 1); ?> km/h
                                </td>
                                <td class="horodatage">
                                    <?php echo date('d/m H:i', strtotime($vehicule['horodatage'])); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- ========== SECTION FACTURES ========== -->
    <section class="section-factures">
        <h2>💼 Factures Récentes</h2>
        <div class="factures-summary">
            <div class="facture-stat">
                <span class="label">Factures Émises</span>
                <span class="value"><?php echo $statsFactures['emise']; ?></span>
            </div>
            <div class="facture-stat">
                <span class="label">Factures Payées</span>
                <span class="value success"><?php echo $statsFactures['payee']; ?></span>
            </div>
            <div class="facture-stat">
                <span class="label">Factures Impayées</span>
                <span class="value danger"><?php echo $statsFactures['impayee']; ?></span>
            </div>
            <div class="facture-stat">
                <span class="label">CA (30j)</span>
                <span class="value"><?php echo number_format($statsFactures['montant_total_ht'], 0); ?>€</span>
            </div>
        </div>
    </section>
</div>