<!DOCTYPE html>
<html lang="fr">
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

    // Récupère les données de la table validation_entreprise
    $stmt = $dbh->prepare("SELECT * FROM validation_entreprise");
    $stmt->execute();
    $entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}


// Fonction pour valider une entreprise
function validerEntreprise($dbh, $id_entreprise)
{
    try {
        // Récupère les données de l'entreprise à valider
        $stmt_select = $dbh->prepare("SELECT * FROM validation_entreprise WHERE id_entreprise = :id");
        $stmt_select->bindParam(':id', $id_entreprise);
        $stmt_select->execute();
        $entreprise_data = $stmt_select->fetch(PDO::FETCH_ASSOC);

        // Insère les données de l'entreprise dans la table entreprise
        $stmt_insert = $dbh->prepare("INSERT INTO entreprise (nom_entreprise, rue_entreprise, cp_entreprise, ville_entreprise, pays_entreprise, tel_entreprise, fax_entreprise, email_entreprise) VALUES (:nom, :rue, :cp, :ville, :pays, :tel, :fax, :email)");
        $stmt_insert->bindParam(':nom', $entreprise_data['nom_entreprise']);
        $stmt_insert->bindParam(':rue', $entreprise_data['rue_entreprise']);
        $stmt_insert->bindParam(':cp', $entreprise_data['cp_entreprise']);
        $stmt_insert->bindParam(':ville', $entreprise_data['ville_entreprise']);
        $stmt_insert->bindParam(':pays', $entreprise_data['pays_entreprise']);
        $stmt_insert->bindParam(':tel', $entreprise_data['tel_entreprise']);
        $stmt_insert->bindParam(':fax', $entreprise_data['fax_entreprise']);
        $stmt_insert->bindParam(':email', $entreprise_data['email_entreprise']);
        $stmt_insert->execute();

        // Supprime la ligne correspondante dans la table validation_entreprise
        $stmt_delete = $dbh->prepare("DELETE FROM validation_entreprise WHERE id_entreprise = :id");
        $stmt_delete->bindParam(':id', $id_entreprise);
        $stmt_delete->execute();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

// Fonction pour refuser une entreprise
function refuserEntreprise($dbh, $id_entreprise)
{
    try {
        // Supprime la ligne correspondante dans la table validation_entreprise
        $stmt_delete = $dbh->prepare("DELETE FROM validation_entreprise WHERE id_entreprise = :id");
        $stmt_delete->bindParam(':id', $id_entreprise);
        $stmt_delete->execute();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
// Traitement de l'action Valider ou Refuser
if (isset($_POST['valider'])) {
    $id_entreprise = $_POST['id_entreprise'];
    validerEntreprise($dbh, $id_entreprise);
    header("Location: validation_entreprises.php"); // Redirige vers cette page pour actualiser la liste après traitement
    exit();
}

if (isset($_POST['refuser'])) {
    $id_entreprise = $_POST['id_entreprise'];
    refuserEntreprise($dbh, $id_entreprise);
    header("Location: validation_entreprises.php"); // Redirige vers cette page pour actualiser la liste après traitement
    exit();
}
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entreprises en attente de validation - Gestion Stage</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #212529;
            color: white;
        }
        .navbar {
            background-color: #343a40;
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
            color: white;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        th {
            background-color: #007bff;
        }
        tr:nth-child(even) {
            background-color: #444;
        }
        form {
            display: inline;
        }
        .footer-clean {
            padding: 50px 0;
            color: #fff;
            background-color: #343a40;
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 50px 0;
            color: #fff;
            background-color: #212529;
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
        .footer-clean .item.social > a {
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
        .footer-clean .item.social > a:hover {
            opacity: 0.9;
        }
        .footer-clean .item > p {
            opacity: 0.6;
            margin-bottom: 0;
        }
        .footer-clean .item > p.terms {
            font-size: 13px;
            margin-top: 2px;
        }
        .footer-clean .item > p.terms > a {
            color: #fff;
            font-weight: bold;
        }
        .footer-clean .item > p.terms > a:hover {
            color: #222;
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
                    <li class="nav-item"><a href="validation_stages.php" class="nav-link">Demande de validation de stages</a></li>
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

    <h1>Entreprises en attente de validation</h1>

    <!-- Tableau avec données -->
    <table>
        <tr>
            <th>Nom de l'entreprise</th>
            <th>Rue</th>
            <th>Code Postal</th>
            <th>Ville</th>
            <th>Pays</th>
            <th>Téléphone</th>
            <th>Fax</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        <?php foreach ($entreprises as $entreprise) : ?>
            <tr>
                <td><?= $entreprise['nom_entreprise'] ?></td>
                <td><?= $entreprise['rue_entreprise'] ?></td>
                <td><?= $entreprise['cp_entreprise'] ?></td>
                <td><?= $entreprise['ville_entreprise'] ?></td>
                <td><?= $entreprise['pays_entreprise'] ?></td>
                <td><?= $entreprise['tel_entreprise'] ?></td>
                <td><?= $entreprise['fax_entreprise'] ?></td>
                <td><?= $entreprise['email_entreprise'] ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="id_entreprise" value="<?= $entreprise['id_entreprise'] ?>">
                        <button type="submit" name="valider" class="btn btn-success">Valider</button>
                        <button type="submit" name="refuser" class="btn btn-danger">Refuser</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>    

    <!-- Footer -->
    <div class="footer-clean bg-dark">
        <footer>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-sm-4 col-md-3 item">
                        <h3>Accès rapide</h3>
                        <ul>
                            <li><a href="/./etudiant/entreprise.php">Entreprises</a></li>
                            <li><a href="/./etudiant/stages.php">Stages</a></li>
                            <li><a href="/./etudiant/conventions.php">Conventions</a></li>
                            <li><a href="index.php">Admin Mode</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-4 col-md-3 item">
                        <h3>Liens</h3>
                        <ul>
                            <li><a href="https://www.ecoledirecte.com/login?cameFrom=%2FAccueil">Ecole Directe</a></li>
                            <li><a href="https://ndlpavranches.fr/">NDLP</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 item social">
                        <p class="copyright">Notre-Dame-La-Providence © 2024</p>
                    </div>
                </div>
            </div>
            <div class="page-footer font-small text-light mt-5">
                <div class="footer-copyright text-center">Créer par
                    <a href="https://monportfolio.cloud/" target="_blank">Vincent GAMBLIN</a>
                    &
                    <a href="https://portfoliodeleo.fr/" target="_blank">Léo GERMAIN</a> 
                    © 2024 Copyright
                </div>
            </div>
        </footer>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"></script>
</body>

</html>

