<?php
require_once("./modele/bd.inc.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $mdp = $_POST["mdp"] ?? "";

    if (empty($email) || empty($mdp)) {
        die("Veuillez remplir tous les champs.");
    }

    try {
        $pdo = Connexion::connexionPDO();

        // Récupération de l'utilisateur
        $sql = "SELECT * FROM user WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":email" => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mdp, $user["mdp"])) {
            // Connexion réussie
            $_SESSION["id_user"] = $user["id_user"];
            $_SESSION["nom"] = $user["nom"];
            $_SESSION["prenom"] = $user["prenom"];
            $_SESSION["email"] = $user["email"];

            echo "Connexion réussie. Bonjour " . htmlspecialchars($user["prenom"]) . " !";
            sleep(0.5);
            header("Location: ./vue/entete.html.php"); // à activer après tests
            exit;
        } else {
            echo "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!-- Formulaire HTML de test -->
<form method="POST" action="">
    <label>Email :</label><br>
    <input type="email" name="email" required><br>

    <label>Mot de passe :</label><br>
    <input type="password" name="mdp" required><br><br>

    <button type="submit">Se connecter</button>
    Pas encore de compte ? <a href="inscription.php" >Cliquez ici pour en créer un.</a>
</form>