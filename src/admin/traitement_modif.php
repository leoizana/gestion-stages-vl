<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier que tous les champs nécessaires sont présents
    if (
        isset($_POST['id_stage']) &&
        isset($_POST['classe_eleve']) &&
        isset($_POST['date_debut']) &&
        isset($_POST['date_fin']) &&
        isset($_POST['session']) &&
        isset($_POST['themes']) &&
        isset($_POST['commentaires']) 
    ) {
        // Récupérer les données du formulaire
        $id_stage = $_POST['id_stage'];
        $classe_eleve = $_POST['classe_eleve'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $session = $_POST['session'];
        $themes = $_POST['themes'];
        $commentaires = $_POST['commentaires'];

        $serveur = "127.0.0.1";
        $utilisateur = "root";
        $mot_de_passe = "root";
        $base_de_donnees = "stages";

        try {
            // Connexion à la base de données
            $dbh = new PDO("mysql:host=$serveur;dbname=$base_de_donnees;charset=utf8", $utilisateur, $mot_de_passe);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Requête de mise à jour avec toutes les nouvelles colonnes
            $stmt = $dbh->prepare("UPDATE stage 
                                   SET classe_eleve = :classe_eleve, date_debut = :date_debut, date_fin = :date_fin, session = :session, 
                                   themes = :themes, commentaires = :commentaires WHERE id_stage = :id_stage");
            // Lier les paramètres
            $stmt->bindParam(':classe_eleve', $classe_eleve);
            $stmt->bindParam(':date_debut', $date_debut);
            $stmt->bindParam(':date_fin', $date_fin);
            $stmt->bindParam(':session', $session);
            $stmt->bindParam(':themes', $themes);
            $stmt->bindParam(':commentaires', $commentaires);
            $stmt->bindParam(':id_stage', $id_stage);

            // Exécution de la requête de mise à jour
            $stmt->execute();

            // Redirection après mise à jour réussie
            header("Location: /./etudiant/stages.php");
            exit();
        } catch (PDOException $e) {
            echo "Erreur : " . htmlspecialchars($e->getMessage());
        }

        // Fermer la connexion à la base de données
        $dbh = null;
    } else {
        echo "Tous les champs du formulaire doivent être remplis.";
    }
} else {
    echo "Le formulaire n'a pas été soumis.";
}
?>
