<?php
include_once '../../modele/bd.inc.php';
include_once '../core/Trajet.php';

class TrajetDAO {
    private PDO $bd;

    public function __construct() {
        $this->bd = Connexion::connexionPDO();
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
            $t = new Trajet(
                $row['id_vehicule'], $row['id_chauffeur'], $row['id_gps'],
                $row['heure_depart_prevue'], $row['heure_arrivee_prevue'] ?? '', $row['statut']
            );
            $t->setId($row['id_trajet']);
            return $t;
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
}
