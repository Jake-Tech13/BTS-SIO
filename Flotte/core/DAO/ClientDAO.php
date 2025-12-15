<?php
include_once '../../modele/bd.inc.php';
include_once '../Client.php';

class ClientDAO {
    private PDO $bd;
    
    public function __construct() {
        $this->bd = Connexion::connexionPDO();
    }
    
/*    public function getClientsLike(string $champ, string $valeur): array {
        $req = $this->bd->prepare("SELECT * FROM client WHERE :champ LIKE %:valeur%");
        $req->bindValue(':champ', $champ, PDO::PARAM_STR);
        $req->bindValue(':valeur', $valeur, PDO::);
        $req->execute();
        $ligne = $req->fetch(PDO::FETCH_ASSOC);

        if ($ligne) {
            return new Client(
                $ligne['id_client'],
                $ligne['nom'],
                $ligne['prenom'],
                $ligne['raison_sociale'],
                $ligne['email'],
                $ligne['tel'],
                $ligne['mdp'],
                $ligne['code_postal'],
                $ligne['ville'],
                $ligne['adresse'],
                $ligne['numero_tva'],
                $ligne['date_creation']
            );
        } else {
            echo "ERROR: No client found with an id of '$id'.";
            return null;
        }
    }*/

    public function creerClient(Client $client): void {
        $req = $this->bd->prepare("INSERT INTO client (nom, prenom, raison_sociale, email, tel, mdp, code_postal, ville, adresse, numero_tva, date_creation)
                                          VALUES (:nom, :prenom, :raison_sociale, :email, :tel, :mdp, :code_postal, :ville, :adresse, :numero_tva, :date_creation)");
        $req->bindValue(':nom', $client->getNom(), PDO::PARAM_STR);
        $req->bindValue(':prenom', $client->getPrenom(), PDO::PARAM_STR);
        $req->bindValue(':raison_sociale', $client->getRaisonSociale(), PDO::PARAM_STR);
        $req->bindValue(':email', $client->getEmail(), PDO::PARAM_STR);
        $req->bindValue(':tel', $client->getTelephone(), PDO::PARAM_STR);
        $req->bindValue(':mdp', $client->getMdp(), PDO::PARAM_STR);
        $req->bindValue(':code_postal', $client->getCodePostal(), PDO::PARAM_STR);
        $req->bindValue(':ville', $client->getVille(), PDO::PARAM_STR);
        $req->bindValue(':adresse', $client->getAdresse(), PDO::PARAM_STR);
        $req->bindValue(':numero_tva', $client->getNumTVA(), PDO::PARAM_STR);
        $req->bindValue(':date_creation', $client->getDateCreation(), PDO::PARAM_STR);
        $req->execute();
    }

    /**
     * Vérifie les identifiants (Email + Mot de passe hashé)
     */
    public function verifierConnexion(string $email, string $mdpClair): ?Client {
        $req = $this->bd->prepare("SELECT * FROM client WHERE email = :email");
        $req->execute([':email' => $email]);
        $row = $req->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($mdpClair, $row['mdp'])) {
            return $this->mapToClient($row);
        }
        return null;
    }

    /**
     * Vérifie si un email existe déjà (pour l'inscription)
     */
    public function emailExiste(string $email): bool {
        $req = $this->bd->prepare("SELECT COUNT(*) FROM client WHERE email = :email");
        $req->execute([':email' => $email]);
        return $req->fetchColumn() > 0;
    }

    /**
     * Met à jour les infos de contact
     */
    public function updateContact(int $idClient, string $tel, string $adresse, string $ville, string $cp): void {
        $sql = "UPDATE client SET tel = :tel, adresse = :adr, ville = :ville, code_postal = :cp WHERE id_client = :id";
        $req = $this->bd->prepare($sql);
        $req->execute([
            ':tel' => $tel, ':adr' => $adresse, ':ville' => $ville, ':cp' => $cp, ':id' => $idClient
        ]);
    }

    public function getById(int $id): ?Client {
        $req = $this->bd->prepare("SELECT * FROM client WHERE id_client = :id");
        $req->execute([':id' => $id]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToClient($row) : null;
    }

    // ... (méthodes existantes...)

    public function getByRaisonSociale(string $raison): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM client WHERE raison_sociale = :val");
        $req->execute([':val' => $raison]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToClient($row); }
        return $res;
    }

    public function getByNom(string $nom): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM client WHERE nom = :val");
        $req->execute([':val' => $nom]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToClient($row); }
        return $res;
    }

    public function getByPrenom(string $prenom): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM client WHERE prenom = :val");
        $req->execute([':val' => $prenom]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToClient($row); }
        return $res;
    }

    // L'email est unique
    public function getByEmail(string $email): ?Client {
        $req = $this->bd->prepare("SELECT * FROM client WHERE email = :val");
        $req->execute([':val' => $email]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToClient($row) : null;
    }

    public function getByTel(string $tel): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM client WHERE tel = :val");
        $req->execute([':val' => $tel]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToClient($row); }
        return $res;
    }

    public function getByCodePostal(string $cp): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM client WHERE code_postal = :val");
        $req->execute([':val' => $cp]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToClient($row); }
        return $res;
    }

    public function getByVille(string $ville): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM client WHERE ville = :val");
        $req->execute([':val' => $ville]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToClient($row); }
        return $res;
    }

    public function getByAdresse(string $adresse): array {
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM client WHERE adresse = :val");
        $req->execute([':val' => $adresse]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToClient($row); }
        return $res;
    }

    public function getByNumeroTva(string $tva): ?Client {
        // Le numéro de TVA est censé être unique
        $req = $this->bd->prepare("SELECT * FROM client WHERE numero_tva = :val");
        $req->execute([':val' => $tva]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToClient($row) : null;
    }

    public function getByDateCreation(string $date): array {
        // Attention au format datetime, ici on cherche l'égalité exacte ou via DATE()
        $res = [];
        $req = $this->bd->prepare("SELECT * FROM client WHERE DATE(date_creation) = DATE(:val)");
        $req->execute([':val' => $date]);
        while ($row = $req->fetch(PDO::FETCH_ASSOC)) { $res[] = $this->mapToClient($row); }
        return $res;
    }

    private function mapToClient(array $row): Client {
        return new Client(
            $row['id_client'], $row['nom'], $row['prenom'], $row['raison_sociale'],
            $row['email'], $row['tel'], $row['mdp'], $row['code_postal'],
            $row['ville'], $row['adresse'], $row['numero_tva'], $row['date_creation']
        );
    }
}