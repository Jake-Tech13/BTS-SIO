<?php
session_start();
?>

<form method="post">
    <button type="submit" name="compte">Compte</button>
</form>

<?php
if (isset($_POST['compte'])) {
    if (isset($_SESSION['utilisateur_id'])) {
        // L'utilisateur est déjà connecté → redirection vers son profil
        header("Location: profil.php");
        exit;
    } else {
        // Pas connecté → redirection vers la page de connexion
        header("Location: connexion.php");
        exit;
    }
}
?>
