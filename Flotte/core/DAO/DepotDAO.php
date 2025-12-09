<?php
include_once '../../modele/bd.inc.php';
include_once '../core/Depot.php';

class DepotDAO {
    private PDO $bd;

    public function __construct() {
        $this->bd = Connexion::connexionPDO();
    }

    public function getById(int $id): ?Depot {
        $req = $this->bd->prepare("SELECT * FROM depot WHERE id_depot = :id");
        $req->execute([':id' => $id]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToDepot($row) : null;
    }

    // --- MÉTHODES MÉTIER UTILES ---

    public function ajouterDepot(Depot $depot): void {
        $req = $this->bd->prepare("INSERT INTO depot (nom, nom_contact, adresse, ville, tel, longitude, latitude)
                                          VALUES (:nom, :nom_contact, :adresse, :ville, :tel, :longitude, :latitude)");
        $req->bindValue(':nom', $depot->getNom(), PDO::PARAM_STR);
        $req->bindValue(':nom_contact', $depot->getNomContact(), PDO::PARAM_STR);
        $req->bindValue(':adresse', $depot->getAdresse(), PDO::PARAM_STR);
        $req->bindValue(':ville', $depot->getVille(), PDO::PARAM_STR);
        $req->bindValue(':tel', $depot->getTelephone(), PDO::PARAM_STR);
        $req->bindValue(':longitude', $depot->getLongitude(), PDO::PARAM_LOB);
        $req->bindValue(':latitude', $depot->getLatitude(), PDO::PARAM_LOB);
        $req->execute();
    }

    /**
     * Trouve le dépôt le plus proche d'une coordonnée GPS (Formule Haversine simplifiée en SQL)
     */
    public function trouverDepotLePlusProche(float $lat, float $lon): ?Depot {
        // Cette requête calcule la distance en KM et trie par proximité
        $sql = "SELECT *, ( 6371 * acos( cos( radians(:lat) ) * cos( radians( latitude ) ) 
                * cos( radians( longitude ) - radians(:lon) ) + sin( radians(:lat) ) 
                * sin( radians( latitude ) ) ) ) AS distance 
                FROM depot 
                ORDER BY distance ASC 
                LIMIT 1";
        
        $req = $this->bd->prepare($sql);
        $req->execute([':lat' => $lat, ':lon' => $lon]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        
        return $row ? $this->mapToDepot($row) : null;
    }

    /**
     * Liste tous les dépôts d'une ville ou région
     */
    public function rechercherParVille(string $ville): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM depot WHERE ville LIKE :ville");
        $req->execute([':ville' => "%$ville%"]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToDepot($row);
        }
        return $res;
    }

    private function mapToDepot(array $row): Depot {
        $d = new Depot(
            $row['nom'], $row['nom_contact'], $row['adresse'],
            $row['ville'], $row['tel'], $row['latitude'], $row['longitude']
        );
        $d->setId($row['id_depot']);
        return $d;
    }
}
