<?php
session_start();

// Vérifie si l'utilisateur est connecté


// Connexion à la base de données
$serveur = "127.0.0.1";
$utilisateur = "root";
$mot_de_passe = "root";
$base_de_donnees = "stages";

try {
    $dbh = new PDO("mysql:host=$serveur;dbname=$base_de_donnees;charset=utf8", $utilisateur, $mot_de_passe);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupère les données de la table validation_stage
    $stmt = $dbh->prepare("SELECT * FROM validation_stage");
    $stmt->execute();
    $stages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

// Fonction pour valider un stage
function validerStage($dbh, $id_stage)
{
    try {
        // Récupère les données du stage à valider
        $stmt_select = $dbh->prepare("SELECT * FROM validation_stage WHERE id_stage = :id");
        $stmt_select->bindParam(':id', $id_stage);
        $stmt_select->execute();
        $stage_data = $stmt_select->fetch(PDO::FETCH_ASSOC);

        // Insère les données du stage dans la table stage
        $stmt_insert = $dbh->prepare("INSERT INTO stage (classe_eleve, date_debut, date_fin, session, themes, commentaires) VALUES (:classe, :debut, :fin, :session, :themes, :commentaires)");
        $stmt_insert->bindParam(':classe', $stage_data['classe_eleve']);
        $stmt_insert->bindParam(':debut', $stage_data['date_debut']);
        $stmt_insert->bindParam(':fin', $stage_data['date_fin']);
        $stmt_insert->bindParam(':session', $stage_data['session']);
        $stmt_insert->bindParam(':themes', $stage_data['themes']);
        $stmt_insert->bindParam(':commentaires', $stage_data['commentaires']);
        $stmt_insert->execute();

        // Supprime la ligne correspondante dans la table validation_stage
        $stmt_delete = $dbh->prepare("DELETE FROM validation_stage WHERE id_stage = :id");
        $stmt_delete->bindParam(':id', $id_stage);
        $stmt_delete->execute();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Fonction pour refuser un stage
function refuserStage($dbh, $id_stage)
{
    try {
        // Supprime la ligne correspondante dans la table validation_stage
        $stmt_delete = $dbh->prepare("DELETE FROM validation_stage WHERE id_stage = :id");
        $stmt_delete->bindParam(':id', $id_stage);
        $stmt_delete->execute();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
<?php
    // Traitement de l'action Valider ou Refuser
    if (isset($_POST['valider'])) {
        $id_stage = $_POST['id_stage'];
        validerStage($dbh, $id_stage);
        header("Location: validation_stages.php"); // Redirige vers cette page pour actualiser la liste après traitement
        exit();
    }

    if (isset($_POST['refuser'])) {
        $id_stage = $_POST['id_stage'];
        refuserStage($dbh, $id_stage);
        header("Location: validation_stages.php"); // Redirige vers cette page pour actualiser la liste après traitement
        exit();
    }
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Stages - Gestion Stage</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/commands.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #212529;
            color: white;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
            position: relative;
            z-index: 1;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
        }

        tr:nth-child(even) {
            background-color: #444;
        }

        .footer-clean {
            padding: 50px 0;
            color: #fff;
            background-color: #343a40;
            position: relative;
            z-index: 2;
        }

        .footer-clean h3 {
            margin-top: 0;
            margin-bottom: 12px;
            font-weight: bold;
            font-size: 16px;
        }

        .footer-clean ul {
            padding: 0;
            list-style: none;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .footer-clean ul a {
            color: inherit;
            text-decoration: none;
            opacity: 0.6;
        }

        .footer-clean ul a:hover {
            opacity: 0.8;
        }

        .footer-clean .item.social {
            text-align: right;
        }

        .footer-clean .item.social>a {
            font-size: 24px;
            width: 40px;
            height: 40px;
            line-height: 40px;
            display: inline-block;
            text-align: center;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.4);
            margin-left: 10px;
            color: #fff;
            opacity: 0.75;
        }

        .footer-clean .item.social>a:hover {
            opacity: 0.9;
        }

        .footer-clean .item>p {
            opacity: 0.6;
            margin-bottom: 0;
        }

        .footer-clean .item>p.terms {
            font-size: 13px;
            margin-top: 2px;
        }

        .footer-clean .item>p.terms>a {
            color: #fff;
            font-weight: bold;
        }

        .footer-clean .item>p.terms>a:hover {
            color: #222;
        }

        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
            margin-left: 10px;
        }

        .btn-outline-danger:hover {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-outline-info {
            color: #007bff;
            border-color: #007bff;
        }

        .btn-outline-info:hover {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        form {
            display: inline;
        }

        th {
            color: white;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <a href="/./index.php" class="navbar-brand text-info">Gestion Stage</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainmenu">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a href="_prof_index.php" class="nav-link">Accueil</a></li>
                    <li class="nav-item"><a href="validation_entreprises.php" class="nav-link">Demande de validation d'entreprises</a></li>
                    <li class="nav-item"><a href="stages_prof.php" class="nav-link">Stages</a></li>
                </ul><br>
                <?php
                if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte'] === true) {
                    echo '<li class="navbar-nav nav-item"><a href="/./logout.php" class="btn btn-outline-danger">Se déconnecter</a></li>';
                    echo '<li class="navbar-nav nav-item"><a href="" class="btn btn-outline-info">'.$_SESSION['prenom'].'</a></li>';
                } else {
                    echo '<li class="navbar-nav nav-item"><a href="/./page_connexion.html" class="btn btn-outline-info">Se connecter</a></li>';
                }
                ?>
            </div>
        </div>
    </nav>

    <h1>Stages en attente de validation</h1>

    <!-- Tableau avec données -->
    <table border="1">
        <tr>
            <th>Classe de l'élève</th>
            <th>Date de début</th>
            <th>Date de fin </th>
            <th>Session</th>
            <th>Thèmes</th>
            <th>Commentaires</th>
            <th>Action</th>
        </tr>
        <?php foreach ($stages as $stage) : ?>
            <tr>
                <td><?= $stage['classe_eleve'] ?></td>
                <td><?= $stage['date_debut'] ?></td>
                <td><?= $stage['date_fin'] ?></td>
                <td><?= $stage['session'] ?></td>
                <td><?= $stage['themes'] ?></td>
                <td><?= $stage['commentaires'] ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="id_stage" value="<?= $stage['id_stage'] ?>">
                        <button type="submit" name="valider" class="btn btn-success">Valider</button>
                        <button type="submit" name="refuser" class="btn btn-danger">Refuser</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>



</body>
</html>