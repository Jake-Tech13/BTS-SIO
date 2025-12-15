<?php
include_once '../../modele/bd.inc.php';
include_once '../core/Gps.php';

class GpsDAO {
    private PDO $bd;

    public function __construct() {
        $this->bd = Connexion::connexionPDO();
    }

    // ... (méthodes existantes...)

    public function getByHorodatage(string $date): array {
        $res = [];
        // Recherche par date/heure exacte ou date seulement selon besoin. Ici date exacte.
        $req = $this->bd->prepare("SELECT * FROM gps WHERE horodatage = :val");
        $req->execute([':val' => $date]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToGps($row); }
        return $res;
    }

    public function getByLatitude(float $lat): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM gps WHERE latitude = :val");
        $req->execute([':val' => $lat]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToGps($row); }
        return $res;
    }

    public function getByLongitude(float $lon): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM gps WHERE longitude = :val");
        $req->execute([':val' => $lon]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToGps($row); }
        return $res;
    }

    public function getByVitesseKmh(float $vitesse): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM gps WHERE vitesse_kmh = :val");
        $req->execute([':val' => $vitesse]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToGps($row); }
        return $res;
    }

    public function getByCap(float $cap): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM gps WHERE cap = :val");
        $req->execute([':val' => $cap]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToGps($row); }
        return $res;
    }

    /**
     * Enregistre une nouvelle position GPS (télémétrie)
     */
    public function enregistrerPosition(Gps $gps): void {
        $sql = "INSERT INTO gps (horodatage, latitude, longitude, vitesse_kmh, cap) 
                VALUES (NOW(), :lat, :lon, :vit, :cap)";
        $req = $this->bd->prepare($sql);
        $req->execute([
            ':lat' => $gps->getLatitude(),
            ':lon' => $gps->getLongitude(),
            ':vit' => $gps->getVitesseKmh(),
            ':cap' => $gps->getCap()
        ]);
        // Note : Idéalement, il faudrait lier cette table à 'vehicule' ou 'trajet' dans la BDD
        // pour savoir à qui appartient la position.
    }

    /**
     * Récupère la dernière position GPS d'un véhicule spécifique. Renvoie un tableau contenant la latitude et la longitude du véhicule, toutes deux de type `float` avec 5 chiffres après la virgule.
     * @param string $immat L'immatriculation du véhicule ciblé.
     * @return array
     */
    public function getDernierePos(string $immat): array {
        $res = [];
        $sql = "SELECT longitude, latitude FROM gps WHERE immatriculation = :immat";
        $req = $this->bd->prepare($sql);
        $req->execute([":immat" => $immat]);
        $res = $req->fetch(PDO::FETCH_ASSOC);

        return $res;
    }

    /**
     * Récupère l'historique complet d'un trajet (Tracé sur carte)
     * Supposons ici que la table GPS soit liée au Trajet, ou qu'on utilise le timestamp.
     * Pour ce code, on utilise une approche par intervalle de temps si le lien n'existe pas.
     */
    public function getHistoriqueTrajet(string $heureDepart, string $heureArrivee): array {
        $res = [];
        $sql = "SELECT * FROM gps WHERE horodatage BETWEEN :debut AND :fin ORDER BY horodatage ASC";
        $req = $this->bd->prepare($sql);
        $req->execute([':debut' => $heureDepart, ':fin' => $heureArrivee]);
        
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToGps($row);
        }
        return $res;
    }

    private function mapToGps(array $row): Gps {
        $g = new Gps(
            $row['horodatage'], $row['latitude'], $row['longitude'],
            $row['vitesse_kmh'], $row['cap']
        );
        $g->setId($row['id_position']);
        return $g;
    }
}
