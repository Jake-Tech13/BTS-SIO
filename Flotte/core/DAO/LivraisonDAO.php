<?php
include_once '../../modele/bd.inc.php';
include_once '../core/Livraison.php';

class LivraisonDAO {
    private PDO $bd;

    public function __construct() {
        $this->bd = Connexion::connexionPDO();
    }

    // CRUD de base
    public function getById(int $id): ?Livraison {
        $req = $this->bd->prepare("SELECT * FROM livraison WHERE id_livraison = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $data = $req->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->mapToLivraison($data) : null;
    }

    // --- MÉTHODES MÉTIER UTILES ---

    /**
     * Récupère les livraisons d'un client spécifique (utile pour l'espace client)
     */
    public function getByClient(int $idClient): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM livraison WHERE id_client = :id ORDER BY date_creation DESC");
        $req->bindValue(':id', $idClient, PDO::PARAM_INT);
        $req->execute();
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToLivraison($row);
        }
        return $res;
    }

    /**
     * Récupère toutes les livraisons en attente d'expédition (pour les planificateurs)
     */
    public function getLivraisonsEnAttente(): array {
        $res = [];
        $req = $this->bd->query("SELECT * FROM livraison WHERE statut = 'prevue' ORDER BY date_livraison_prevue ASC");
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
            $res[] = $this->mapToLivraison($row);
        }
        return $res;
    }

    /**
     * Recherche avancée : permet de trouver une livraison par sa référence unique
     */
    public function rechercheParReference(string $ref): ?Livraison {
        $req = $this->bd->prepare("SELECT * FROM livraison WHERE reference LIKE :ref");
        $req->bindValue(':ref', "%$ref%", PDO::PARAM_STR);
        $req->execute();
        $data = $req->fetch(PDO::FETCH_ASSOC);
        return $data ? $this->mapToLivraison($data) : null;
    }

    /**
     * Statistiques : Volume total transporté sur une période donnée
     */
    public function getVolumeTotal(string $dateDebut, string $dateFin): float {
        $req = $this->bd->prepare("SELECT SUM(volume_m3) as total FROM livraison WHERE date_creation BETWEEN :debut AND :fin");
        $req->bindValue(':debut', $dateDebut);
        $req->bindValue(':fin', $dateFin);
        $req->execute();
        return (float) $req->fetchColumn();
    }

    /**
     * Mise à jour rapide du statut (ex: quand le chauffeur valide la livraison)
     */
    public function updateStatut(int $id, string $statut): bool {
        $req = $this->bd->prepare("UPDATE livraison SET statut = :statut WHERE id_livraison = :id");
        return $req->execute([':statut' => $statut, ':id' => $id]);
    }

    // Helper pour transformer la ligne BDD en objet
    private function mapToLivraison(array $row): Livraison {
        $l = new Livraison(
            $row['id_client'], $row['id_marchandise'], $row['id_destination_depot'],
            $row['poids_kg'], $row['volume_m3'], $row['statut']
        );
        $l->setId($row['id_livraison']);
        $l->setReference($row['reference']);
        $l->setDateCreation($row['date_creation']);
        $l->setDateLivraisonPrevue($row['date_livraison_prevue'] ?? '');
        return $l;
    }
}
