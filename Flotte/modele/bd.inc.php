<?php
class ConnexionBD {
    public static function connexionPDO() {
        $login = "dufourgi";
        $mdp = "05022006";
        $bd = "flotte_bd";
        $serveur = "192.168.20.15"; #mariadb.btssiobayonne.fr

        try {
            $conn = new PDO("mysql:host=$serveur;dbname=$bd", $login, $mdp, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')); 
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            print "Erreur de connexion PDO: $e ";
            die();
        }
        
        if ($_SERVER["SCRIPT_FILENAME"] == __FILE__) {
            // prog de test
            header('Content-Type:text/plain');

            echo "connexionPDO() : \n";
            print_r(connexionPDO());
        }
    }
}
?>
