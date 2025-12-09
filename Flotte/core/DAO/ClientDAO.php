<?php
include_once '../../modele/bd.inc.php';
include_once '../Client.php';

class ClientDAO {
    private PDO $bd;
    
    public function __construct() {
        $this->bd = ConnexionBD::connexionPDO();
    }
    
    public function getClientById(int $id): ?Client {
        $req = $this->bd->prepare("SELECT * FROM client WHERE id_client = :id");
        $req->bindValue(':id', $id, PDO::PARAM_INT);
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
        $req = $this->bd->prepare("INSERT INTO TABLE client
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
}