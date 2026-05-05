<?php
require_once("./modele/bd.inc.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"] ?? "");
    $prenom = trim($_POST["prenom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mdp = $_POST["mdp"] ?? "";

    if (empty($nom) || empty($prenom) || empty($email) || empty($mdp)) {
        die("Veuillez remplir tous les champs.");
    }

    try {
        $pdo = Connexion::connexionPDO();

        // Vérifier si l'email existe déjà
        $check = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = :email");
        $check->execute([":email" => $email]);
        if ($check->fetchColumn() > 0) {
            die("Un compte existe déjà avec cet email. Veuillez sélectionner un e-mail différent.");
        }

        // Hachage du mot de passe
        $hash = password_hash($mdp, PASSWORD_DEFAULT);

        // Insertion
        $sql = "INSERT INTO user (nom, prenom, email, mdp, date_creation) 
                VALUES (:nom, :prenom, :email, :mdp, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":nom" => $nom,
            ":prenom" => $prenom,
            ":email" => $email,
            ":mdp" => $hash
        ]);

        echo "Inscription réussie ! Vous pouvez maintenant vous connecter avec votre nouveau compte.";

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!-- Formulaire HTML de test -->
<form method="POST" action="">
    <label>Nom :</label><br>
    <input type="text" name="nom" required><br>

    <label>Prénom :</label><br>
    <input type="text" name="prenom" required><br>

    <label>Raison sociale :</label><br>
    <input type="text" name="r_sociale" required><br>

    <label>E-mail :</label><br>
    <input type="email" name="email" required><br>
    
    <label>Téléphone :</label><br>
    <input type="tel" name="tel" required><br>
    
    <label>Code postal :</label><br>
    <input type="text" name="cp" required><br>
    
    <label>Ville :</label><br>
    <input type="text" name="ville" required><br>
    
    <label>Adresse :</label><br>
    <input type="text" name="adresse" required><br>
    
    <label>N° TVA :</label><br>
    <input type="text" name="n_tva" required><br>
    
    <label>Mot de passe :</label><br>
    <input type="password" name="mdp" required><br><br>
    
    <p>REMARQUE: Tous les champs doivent être remplis.</p>
    <button type="submit">Créer un compte</button>
</form>
