<?php
include_once '../../modele/bd.inc.php';
include_once '../core/Trajet.php';

class TrajetDAO {
    private PDO $bd;

    public function __construct() {
        $this->bd = Connexion::connexionPDO();
    }

    // ... (méthodes existantes...)

    public function getByIdVehicule(int $idVehicule): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM trajet WHERE id_vehicule = :val");
        $req->execute([':val' => $idVehicule]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToTrajet($row); }
        return $res;
    }

    public function getByIdChauffeur(int $idChauffeur): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM trajet WHERE id_chauffeur = :val");
        $req->execute([':val' => $idChauffeur]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToTrajet($row); }
        return $res;
    }

    public function getByIdGps(int $idGps): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM trajet WHERE id_gps = :val");
        $req->execute([':val' => $idGps]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToTrajet($row); }
        return $res;
    }

    public function getByHeureDepartPrevue(string $heure): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM trajet WHERE heure_depart_prevue = :val");
        $req->execute([':val' => $heure]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToTrajet($row); }
        return $res;
    }

    public function getByHeureDepartReelle(string $heure): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM trajet WHERE heure_depart_reelle = :val");
        $req->execute([':val' => $heure]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToTrajet($row); }
        return $res;
    }

    public function getByHeureArriveePrevue(string $heure): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM trajet WHERE heure_arrivee_prevue = :val");
        $req->execute([':val' => $heure]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToTrajet($row); }
        return $res;
    }

    public function getByHeureArriveeReelle(string $heure): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM trajet WHERE heure_arrivee_reelle = :val");
        $req->execute([':val' => $heure]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToTrajet($row); }
        return $res;
    }

    public function getByStatut(string $statut): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM trajet WHERE statut = :val");
        $req->execute([':val' => $statut]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToTrajet($row); }
        return $res;
    }

    public function getByNotes(string $notes): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM trajet WHERE notes = :val");
        $req->execute([':val' => $notes]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToTrajet($row); }
        return $res;
    }

    /**
     * Création d'un nouveau trajet (Assignation Chauffeur/Véhicule)
     */
    public function creerTrajet(Trajet $trajet): int {
        $sql = "INSERT INTO trajet (id_vehicule, id_chauffeur, id_gps, heure_depart_prevue, statut) 
                VALUES (:vehicule, :chauffeur, :gps, :h_depart, 'prevu')";
        $req = $this->bd->prepare($sql);
        $req->execute([
            ':vehicule' => $trajet->getIdVehicule(),
            ':chauffeur' => $trajet->getIdChauffeur(),
            ':gps' => $trajet->getIdGps(), // ID initial de position (souvent le dépôt)
            ':h_depart' => $trajet->getHeureDepartPrevue()
        ]);
        return (int) $this->bd->lastInsertId();
    }

    /**
     * Récupère le trajet actif d'un chauffeur (pour son interface mobile/tablette)
     */
    public function getTrajetActifChauffeur(int $idChauffeur): ?Trajet {
        $req = $this->bd->prepare("SELECT * FROM trajet WHERE id_chauffeur = :id AND statut = 'en_cours'");
        $req->execute([':id' => $idChauffeur]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            return $this->mapToTrajet($row);
        }
        return null;
    }

    /**
     * Met à jour la position GPS courante d'un trajet
     */
    public function updatePositionTrajet(int $idTrajet, int $idNouveauGps): void {
        $req = $this->bd->prepare("UPDATE trajet SET id_gps = :gps WHERE id_trajet = :id");
        $req->execute([':gps' => $idNouveauGps, ':id' => $idTrajet]);
    }

    private function mapToTrajet(array $row): Trajet {
        $t = new Trajet(
            $row['id_vehicule'], $row['id_chauffeur'], $row['id_gps'],
            $row['heure_depart_prevue'], $row['heure_arrivee_prevue'] ?? '', $row['statut']
        );
        $t->setId($row['id_trajet']);
        return $t;
    }
}
