<?php
include_once '../../modele/bd.inc.php';
include_once '../core/Marchandise.php';

class MarchandiseDAO {
    private PDO $bd;

    public function __construct() {
        $this->bd = Connexion::connexionPDO();
    }

    public function getById(int $id): ?Marchandise {
        $req = $this->bd->prepare("SELECT * FROM marchandise WHERE id_marchandise = :id");
        $req->execute([':id' => $id]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToMarchandise($row) : null;
    }

    // --- MÉTHODES MÉTIER UTILES ---

    /**
     * Liste toutes les marchandises classées comme dangereuses
     */
    public function getMarchandisesDangereuses(): array {
        $res = [];
        $req = $this->bd->query("SELECT * FROM marchandise WHERE classe_danger IS NOT NULL AND classe_danger != 'non_classee'");
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToMarchandise($row);
        }
        return $res;
    }

    /**
     * Recherche de marchandise par nom (pour l'autocomplétion)
     */
    public function rechercheParNom(string $nom): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM marchandise WHERE nom LIKE :nom LIMIT 20");
        $req->execute([':nom' => "%$nom%"]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToMarchandise($row);
        }
        return $res;
    }

    private function mapToMarchandise(array $row): Marchandise {
        $m = new Marchandise(
            $row['nom'], $row['num_un'] ?? '', $row['classe_danger'] ?? '',
            $row['etat'], $row['consignes_manipulation'] ?? '', 
            $row['restrictions_transport'] ?? ''
        );
        $m->setId($row['id_marchandise']);
        $m->setDateCreation($row['date_creation']);
        return $m;
    }
}
