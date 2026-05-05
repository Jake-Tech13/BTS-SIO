<?php
session_start();
?>

<html>
    <form method="post">
        <button type="submit" name="compte">Compte</button>
    </form>
</html>

<?php
if (isset($_POST['compte'])) {
    if (isset($_SESSION['id_user'])) {
        // L'utilisateur est déjà connecté → redirection vers son profil
        header("Location: vue/entete.html.php");
        exit;
    } else {
        // Pas connecté → redirection vers la page de connexion
        header("Location: connexion.php");
        exit;
    }
}
?>
