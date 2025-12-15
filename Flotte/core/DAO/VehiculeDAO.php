<?php
include_once '../../modele/bd.inc.php';
include_once '../core/Vehicule.php';

class VehiculeDAO {
    private PDO $bd;

    public function __construct() {
        $this->bd = Connexion::connexionPDO();
    }

    public function getById(int $id): ?Vehicule {
        $req = $this->bd->prepare("SELECT * FROM vehicule WHERE id_vehicule = :id");
        $req->execute([':id' => $id]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToVehicule($row) : null;
    }

    // L'immatriculation est UNIQUE
    public function getByImmatriculation(string $immat): ?Vehicule {
        $req = $this->bd->prepare("SELECT * FROM vehicule WHERE immatriculation = :val");
        $req->execute([':val' => $immat]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToVehicule($row) : null;
    }

    // Le Code VIN est souvent UNIQUE
    public function getByCodeVin(string $vin): ?Vehicule {
        $req = $this->bd->prepare("SELECT * FROM vehicule WHERE code_vin = :val");
        $req->execute([':val' => $vin]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToVehicule($row) : null;
    }

    public function getByModele(string $modele): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM vehicule WHERE modele = :val");
        $req->execute([':val' => $modele]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToVehicule($row); }
        return $res;
    }

    public function getByAnnee(int $annee): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM vehicule WHERE annee = :val");
        $req->execute([':val' => $annee]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToVehicule($row); }
        return $res;
    }

    public function getByCapaciteKg(float $capa): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM vehicule WHERE capacite_kg = :val");
        $req->execute([':val' => $capa]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToVehicule($row); }
        return $res;
    }

    public function getByCapaciteM3(float $capa): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM vehicule WHERE capacite_m3 = :val");
        $req->execute([':val' => $capa]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToVehicule($row); }
        return $res;
    }

    public function getByStatut(string $statut): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM vehicule WHERE statut = :val");
        $req->execute([':val' => $statut]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToVehicule($row); }
        return $res;
    }

    public function getByIdDepotActuel(int $idDepot): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM vehicule WHERE id_depot_actuel = :val");
        $req->execute([':val' => $idDepot]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToVehicule($row); }
        return $res;
    }

    // --- MÉTHODES MÉTIER UTILES ---

    /**
     * Liste les véhicules présents physiquement dans un dépôt spécifique
     */
    public function getVehiculesAuDepot(int $idDepot): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM vehicule WHERE id_depot_actuel = :id AND statut = 'disponible'");
        $req->execute([':id' => $idDepot]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToVehicule($row);
        }
        return $res;
    }

    /**
     * Trouve un véhicule capable de transporter une certaine charge (Poids/Volume)
     */
    public function trouverVehiculeAdapte(float $poidsNecessaire, float $volumeNecessaire): array {
        $res = [];
        $sql = "SELECT * FROM vehicule 
                WHERE statut = 'disponible' 
                AND capacite_kg >= :poids 
                AND capacite_m3 >= :volume
                ORDER BY capacite_kg ASC"; // On prend le plus petit véhicule capable (optimisation)
        
        $req = $this->bd->prepare($sql);
        $req->execute([':poids' => $poidsNecessaire, ':volume' => $volumeNecessaire]);
        
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToVehicule($row);
        }
        return $res;
    }

    /**
     * Récupère tous les véhicules hors service (panne ou entretien) pour le chef d'atelier
     */
    public function getFlotteIndisponible(): array {
        $res = [];
        $req = $this->bd->query("SELECT * FROM vehicule WHERE statut IN ('hors_service', 'en_entretien')");
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToVehicule($row);
        }
        return $res;
    }

    private function mapToVehicule(array $row): Vehicule {
        $v = new Vehicule(
            $row['immatriculation'], $row['code_vin'], $row['modele'],
            $row['annee'], $row['capacite_kg'], $row['capacite_m3'],
            $row['statut'], $row['id_depot_actuel']
        );
        $v->setId($row['id_vehicule']);
        return $v;
    }
}
