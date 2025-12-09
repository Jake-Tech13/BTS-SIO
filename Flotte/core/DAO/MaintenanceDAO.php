<?php
include_once '../../modele/bd.inc.php';
include_once '../core/Maintenance.php';

class MaintenanceDAO {
    private PDO $bd;

    public function __construct() {
        $this->bd = Connexion::connexionPDO();
    }

    /**
     * Récupère l'historique complet d'un véhicule (Carnet d'entretien)
     */
    public function getHistoriqueVehicule(int $idVehicule): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM maintenance WHERE id_vehicule = :id ORDER BY date_intervention DESC");
        $req->execute([':id' => $idVehicule]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToMaintenance($row);
        }
        return $res;
    }

    /**
     * Calcule le coût total de maintenance pour l'année en cours (Budget)
     */
    public function getCoutTotalAnnee(int $annee): float {
        $req = $this->bd->prepare("SELECT SUM(cout) FROM maintenance WHERE YEAR(date_intervention) = :annee");
        $req->execute([':annee' => $annee]);
        return (float) $req->fetchColumn();
    }

    /**
     * Identifie les maintenances en retard (Date prévue dépassée et non terminée)
     */
    public function getAlertesRetard(): array {
        $res = [];
        // Sélectionne les maintenances prévues dont la date est passée
        $sql = "SELECT * FROM maintenance WHERE statut = 'prevue' AND date_intervention < CURDATE()";
        $req = $this->bd->query($sql);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToMaintenance($row);
        }
        return $res;
    }

    private function mapToMaintenance(array $row): Maintenance {
        $m = new Maintenance(
            $row['id_vehicule'], $row['date_intervention'], $row['type_intervention'],
            $row['description'], $row['cout'], $row['odometre_km'] ?? 0, $row['statut']
        );
        $m->setId($row['id_maintenance']);
        return $m;
    }
}
?>