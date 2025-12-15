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

    public function getByNom(string $nom): array {
    $res = [];
    $req = $this->bd->prepare("SELECT * FROM chauffeur WHERE nom = :val");
    $req->execute([':val' => $nom]);
    while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToChauffeur($row); }
    return $res;
    }

    public function getByPrenom(string $prenom): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM chauffeur WHERE prenom = :val");
        $req->execute([':val' => $prenom]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToChauffeur($row); }
        return $res;
    }

    public function getByTelephone(string $telephone): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM chauffeur WHERE telephone = :val");
        $req->execute([':val' => $telephone]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToChauffeur($row); }
        return $res;
    }

    // Le numéro de permis est souvent unique, on renvoie un objet
    public function getByNumeroPermis(string $permis): ?Chauffeur {
        $req = $this->bd->prepare("SELECT * FROM chauffeur WHERE numero_permis = :val");
        $req->execute([':val' => $permis]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToChauffeur($row) : null;
    }

    public function getByDateEmbauche(string $date): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM chauffeur WHERE date_embauche = :val");
        $req->execute([':val' => $date]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToChauffeur($row); }
        return $res;
    }

    public function getByStatut(string $statut): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM chauffeur WHERE statut = :val");
        $req->execute([':val' => $statut]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToChauffeur($row); }
        return $res;
    }

    public function getByCertifications(string $certif): array {
        // Recherche stricte. Pour une recherche partielle, utiliser LIKE dans une autre méthode.
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM chauffeur WHERE certifications = :val");
        $req->execute([':val' => $certif]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToChauffeur($row); }
        return $res;
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
