<?php
// Vérifiez si l'identifiant de l'entreprise est présent dans la requête GET
if (isset($_GET['id_stage'])) {
    // Connexion à la base de données
    $serveur = "127.0.0.1";
    $utilisateur = "root";
    $mot_de_passe = "root";
    $base_de_donnees = "stages";

    try {
        $dbh = new PDO("mysql:host=$serveur;dbname=$base_de_donnees;charset=utf8", $utilisateur, $mot_de_passe);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Préparez la requête SQL pour supprimer l'entreprise avec l'identifiant spécifié
        $stmt = $dbh->prepare("DELETE FROM stage WHERE id_stage = :id_stage");
        $stmt->bindParam(':id_stage', $_GET['id_stage']);
        
        // Exécutez la requête SQL
        $stmt->execute();

        // Redirigez l'utilisateur vers la page principale après la suppression
        header("Location: stages_admin.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . htmlspecialchars($e->getMessage());
    }

    $dbh = null; // Fermer la connexion
}
?>
