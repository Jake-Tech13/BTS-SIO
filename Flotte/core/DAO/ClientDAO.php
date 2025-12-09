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

    private function mapToClient(array $row): Client {
        return new Client(
            $row['id_client'], $row['nom'], $row['prenom'], $row['raison_sociale'],
            $row['email'], $row['tel'], $row['mdp'], $row['code_postal'],
            $row['ville'], $row['adresse'], $row['numero_tva'], $row['date_creation']
        );
    }
}