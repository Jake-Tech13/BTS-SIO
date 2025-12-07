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
    
    public function getClientListLike(string $champ, string $valeur): array {
        $req = $this->bd->prepare("SELECT * FROM client WHERE :champ LIKE %:valeur%");
        $req->bindValue(':champ', $champ, PDO::PARAM_INT);
        $req->bindValue(':valeur', $valeur, PDO::PARAM_INT);
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
}