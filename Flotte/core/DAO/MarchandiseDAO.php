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

    public function getByNom(string $nom): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM marchandise WHERE nom = :val");
        $req->execute([':val' => $nom]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToMarchandise($row); }
        return $res;
    }

    public function getByNumUn(string $num): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM marchandise WHERE num_un = :val");
        $req->execute([':val' => $num]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToMarchandise($row); }
        return $res;
    }

    public function getByClasseDanger(string $classe): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM marchandise WHERE classe_danger = :val");
        $req->execute([':val' => $classe]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToMarchandise($row); }
        return $res;
    }

    public function getByEtat(string $etat): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM marchandise WHERE etat = :val");
        $req->execute([':val' => $etat]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToMarchandise($row); }
        return $res;
    }

    public function getByConsignesManipulation(string $consignes): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM marchandise WHERE consignes_manipulation = :val");
        $req->execute([':val' => $consignes]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToMarchandise($row); }
        return $res;
    }

    public function getByRestrictionsTransport(string $restrictions): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM marchandise WHERE restrictions_transport = :val");
        $req->execute([':val' => $restrictions]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToMarchandise($row); }
        return $res;
    }

    public function getByDateCreation(string $date): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM marchandise WHERE DATE(date_creation) = DATE(:val)");
        $req->execute([':val' => $date]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToMarchandise($row); }
        return $res;
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
