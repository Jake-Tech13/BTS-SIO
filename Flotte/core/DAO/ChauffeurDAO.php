<?php
include_once '../../modele/bd.inc.php';
include_once '../core/Chauffeur.php';

class ChauffeurDAO {
    private PDO $bd;

    public function __construct() {
        $this->bd = Connexion::connexionPDO();
    }

    public function getById(int $id): ?Chauffeur {
        $req = $this->bd->prepare("SELECT * FROM chauffeur WHERE id_chauffeur = :id");
        $req->execute([':id' => $id]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToChauffeur($row) : null;
    }

    // --- MÉTHODES MÉTIER UTILES ---

    /**
     * Trouve les chauffeurs disponibles (qui ne sont pas actuellement en trajet 'en_cours')
     */
    public function getChauffeursDisponibles(): array {
        $res = [];
        // On sélectionne les chauffeurs actifs qui ne sont PAS dans la liste des trajets en cours
        $sql = "SELECT * FROM chauffeur 
                WHERE statut = 'actif' 
                AND id_chauffeur NOT IN (
                    SELECT id_chauffeur FROM trajet WHERE statut = 'en_cours'
                )";
        $req = $this->bd->query($sql);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToChauffeur($row);
        }
        return $res;
    }

    /**
     * Recherche les chauffeurs ayant des certifications spécifiques (ex: "ADR" pour matières dangereuses)
     */
    public function getChauffeursQualifies(string $certification): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM chauffeur WHERE certifications LIKE :certif AND statut = 'actif'");
        $req->execute([':certif' => "%$certification%"]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToChauffeur($row);
        }
        return $res;
    }

    /**
     * Statistiques : Nombre de livraisons effectuées par un chauffeur
     */
    public function getPerformance(int $idChauffeur): int {
        $sql = "SELECT COUNT(*) FROM trajet WHERE id_chauffeur = :id AND statut = 'termine'";
        $req = $this->bd->prepare($sql);
        $req->execute([':id' => $idChauffeur]);
        return (int) $req->fetchColumn();
    }

    private function mapToChauffeur(array $row): Chauffeur {
        $c = new Chauffeur(
            $row['nom'], $row['prenom'], $row['telephone'],
            $row['numero_permis'], $row['date_embauche'],
            $row['statut'], $row['certifications'] ?? ''
        );
        $c->setId($row['id_chauffeur']);
        return $c;
    }
}
