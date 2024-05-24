<?php
// Vérifie si l'identifiant de la ligne à modifier est passé en paramètre
if (isset($_GET['id_stage']) && !empty($_GET['id_stage'])) {
    $id = $_GET['id_stage'];  // Utilisez le paramètre correct

    // Configuration de la base de données
    $serveur = "127.0.0.1"; 
    $utilisateur = "root"; 
    $mot_de_passe = "root"; 
    $base_de_donnees = "stages"; 

    try {
        // Connexion à la base de données
        $dbh = new PDO("mysql:host=$serveur;dbname=$base_de_donnees;charset=utf8", $utilisateur, $mot_de_passe);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête pour obtenir les données de la ligne à modifier
        $stmt = $dbh->prepare("SELECT id_stage, classe_eleve, date_debut, date_fin, session, themes, commentaires FROM stage WHERE id_stage = :id_stage");
        $stmt->bindParam(':id_stage', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si la ligne correspondante existe
        if ($row) {
?>
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="utf-8">
                <title>Modifier une ligne</title>
                <link href="../css/bootstrap.min.css" rel="stylesheet">
                <link href="../css/style.css" rel="stylesheet">
            </head>
            <body>
                <h1>Modifier les informations de l'entreprise</h1>
                <form action="/./admin/traitement_modif.php" method="POST">
                    <input type="hidden" name="id_stage" value="<?php echo htmlspecialchars($id); ?>">

                    <label for="classe_eleve">Classe de l'élève:</label><br>
                    <input type="text" id="classe_eleve" name="classe_eleve" value="<?php echo htmlspecialchars($row['classe_eleve']); ?>"><br>

                    <label for="date_debut">Date de début:</label><br>
                    <input type="date" id="date_debut" name="date_debut" value="<?php echo htmlspecialchars($row['date_debut']); ?>"><br>

                    <label for="date_fin">Date de fin:</label><br>
                    <input type="date" id="date_fin" name="date_fin" value="<?php echo htmlspecialchars($row['date_fin']); ?>"><br>
                    
                    <label for="session">Session:</label><br>
                    <input type="text" id="session" name="session" value="<?php echo htmlspecialchars($row['session']); ?>"><br>
                    
                    <label for="themes">Thèmes:</label><br>
                    <input type="text" id="themes" name="themes" value="<?php echo htmlspecialchars($row['themes']); ?>"><br>

                    <label for="commentaires">Commentaires</label><br>
                    <input type="commentaires" id="commentaires" name="commentaires" value="<?php echo htmlspecialchars($row['commentaires']); ?>"><br>
                    <br>
                    <input type="submit" value="Modifier">
                </form>
            </body>
            </html>
<?php
        } else {
            echo "Aucune ligne correspondant à cet identifiant n'a été trouvée.";
        }

    } catch (PDOException $e) {
        echo "Erreur : " . htmlspecialchars($e->getMessage());
    }

} else {
    echo "Aucun identifiant de ligne n'a été fourni.";
}
?>
