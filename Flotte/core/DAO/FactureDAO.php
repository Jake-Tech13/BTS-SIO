<?php
include_once '../../modele/bd.inc.php';
include_once '../core/Facture.php';

class FactureDAO {
    private PDO $bd;

    public function __construct() {
        $this->bd = Connexion::connexionPDO();
    }

    // méthodes d'instantiation de l'objet métier
    public function getById(int $id): ?Facture {
        $req = $this->bd->prepare("SELECT * FROM facture WHERE id_facture = :id");
        $req->execute([':id' => $id]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToFacture($row) : null;
    }

    public function getByIdLivraison(int $idLivraison): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM facture WHERE id_livraison = :val");
        $req->execute([':val' => $idLivraison]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToFacture($row); }
        return $res;
    }

    public function getByMontantHt(float $montant): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM facture WHERE montant_ht = :val");
        $req->execute([':val' => $montant]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToFacture($row); }
        return $res;
    }

    public function getByTva(float $tva): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM facture WHERE tva = :val");
        $req->execute([':val' => $tva]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToFacture($row); }
        return $res;
    }

    public function getByStatut(string $statut): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM facture WHERE statut = :val");
        $req->execute([':val' => $statut]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToFacture($row); }
        return $res;
    }

    public function getByDateEmission(string $date): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM facture WHERE DATE(date_emission) = DATE(:val)");
        $req->execute([':val' => $date]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToFacture($row); }
        return $res;
    }

    public function getByDatePaiement(string $date): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM facture WHERE DATE(date_paiement) = DATE(:val)");
        $req->execute([':val' => $date]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToFacture($row); }
        return $res;
    }

    // Référence externe est souvent unique ou quasi-unique
    public function getByReferenceExterne(string $ref): ?Facture {
        $req = $this->bd->prepare("SELECT * FROM facture WHERE reference_externe = :val");
        $req->execute([':val' => $ref]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToFacture($row) : null;
    }

    // --- MÉTHODES MÉTIER UTILES ---

    /**
     * Calcule le Chiffre d'Affaires (HT) réalisé sur une période
     */
    public function getChiffreAffaires(string $dateDebut, string $dateFin): float {
        $sql = "SELECT SUM(montant_ht) FROM facture WHERE date_emission BETWEEN :debut AND :fin";
        $req = $this->bd->prepare($sql);
        $req->execute([':debut' => $dateDebut, ':fin' => $dateFin]);
        return (float) $req->fetchColumn();
    }

    /**
     * Récupère toutes les factures en retard de paiement
     */
    public function getFacturesEnRetard(int $joursDelai = 30): array {
        $res = [];
        // Factures émises il y a plus de X jours et toujours pas payées
        $sql = "SELECT * FROM facture 
                WHERE statut = 'emise' 
                AND date_emission < DATE_SUB(NOW(), INTERVAL :jours DAY)";
        
        $req = $this->bd->prepare($sql);
        $req->execute([':jours' => $joursDelai]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToFacture($row);
        }
        return $res;
    }

    /**
     * Récupère les factures d'un client spécifique (via jointure avec Livraison)
     */
    public function getFacturesByClient(int $idClient): array {
        $res = [];
        $sql = "SELECT f.* FROM facture f 
                JOIN livraison l ON f.id_livraison = l.id_livraison 
                WHERE l.id_client = :id
                ORDER BY f.date_emission DESC";
        $req = $this->bd->prepare($sql);
        $req->execute([':id' => $idClient]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToFacture($row);
        }
        return $res;
    }

    private function mapToFacture(array $row): Facture {
        $f = new Facture(
            $row['id_livraison'], $row['montant_ht'], $row['tva'],
            $row['statut'], $row['reference_externe']
        );
        $f->setId($row['id_facture']);
        $f->setDateEmission($row['date_emission']);
        $f->setDatePaiement($row['date_paiement'] ?? '');
        return $f;
    }
}
