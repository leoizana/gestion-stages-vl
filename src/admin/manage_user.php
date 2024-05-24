<!DOCTYPE html>
<html lang="fr">
<?php
session_start();

// Connexion à la base de données (à adapter selon votre configuration)
$serveur = "127.0.0.1";
$base_de_donnees = "stages";
$utilisateur = "root";
$mot_de_passe = "root";

try {
    $dbh = new PDO("mysql:host=$serveur;dbname=$base_de_donnees;charset=utf8", $utilisateur, $mot_de_passe);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $utilisateur_id = $_POST['utilisateur_id'];
        $action = $_POST['action'];

        if ($action === 'changer_role' && isset($_SESSION['role']) && $_SESSION['role'] != 4) {
            $nouveau_role = $_POST['nouveau_role'];
            $stmt = $dbh->prepare("UPDATE eleve SET roles_p = :nouveau_role WHERE id_eleve = :utilisateur_id");
            $stmt->bindParam(':nouveau_role', $nouveau_role);
            $stmt->bindParam(':utilisateur_id', $utilisateur_id);
            $stmt->execute();
        } elseif ($action === 'modifier_mot_de_passe') {
            $nouveau_mot_de_passe = password_hash($_POST['nouveau_mot_de_passe'], PASSWORD_DEFAULT);
            $stmt = $dbh->prepare("UPDATE eleve SET mdp = :nouveau_mot_de_passe WHERE id_eleve = :utilisateur_id");
            $stmt->bindParam(':nouveau_mot_de_passe', $nouveau_mot_de_passe);
            $stmt->bindParam(':utilisateur_id', $utilisateur_id);
            $stmt->execute();
        } elseif ($action === 'supprimer_utilisateur' && $_SESSION['role'] != 4) {
            $stmt = $dbh->prepare("DELETE FROM eleve WHERE id_eleve = :utilisateur_id");
            $stmt->bindParam(':utilisateur_id', $utilisateur_id);
            $stmt->execute();
        }
    }

    $search_query = '';
    if (isset($_GET['search'])) {
        $search_query = $_GET['search'];
        $stmt = $dbh->prepare("SELECT * FROM eleve WHERE nom_eleve LIKE :search OR prenom_eleve LIKE :search OR mail_eleve LIKE :search");
        $stmt->execute([':search' => '%' . $search_query . '%']);
    } else {
        $stmt = $dbh->prepare("SELECT * FROM eleve");
        $stmt->execute();
    }
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/commands.css" rel="stylesheet">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #0dcadc;
            color: white;
        }

        .user-info {
            float: right;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-md bg-dark navbar-dark">
        <div class="container">
            <a href="/./index.php" class="navbar-brand text-info">Gestion Stage</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu"><span
                    class="navbar-toggler-icon"></span></button>

            <div class="collapse navbar-collapse" id="mainmenu">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a href="_admin_index.php" class="nav-link">Accueil</a></li>
                    <li class="nav-item"><a href="stages_admin.php" class="nav-link">Stages</a></li>
                    <li class="nav-item"><a href="manage_user.php" class="nav-link">Gestion des utilisateurs</a></li>
                </ul>
                <br>
                <?php
                if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte'] === true) {
                    echo '<li class="navbar-nav nav-item"><a href="logout.php" class="btn btn-outline-danger">Se déconnecter</a></li>';
                    echo '<li class="navbar-nav nav-item"><a href="" class="btn btn-outline-info">' . $_SESSION['prenom'] . '</a></li>';
                } else {
                    echo '<li class="navbar-nav nav-item"><a href="page_connexion.html" class="btn btn-outline-info">Se connecter</a></li>';
                }
                ?>
            </div>
        </div>
    </nav>

    <div class="container">
        <section style="margin-top: 9%;">
            <div class="row">
                <div class="col-sm-6 my-1">
                    <h2 class="fw-bold">Liste des Utilisateurs</h2>
                    <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="text" name="search" placeholder="Rechercher par nom ou email"
                            value="<?php echo htmlspecialchars($search_query); ?>">
                        <input type="submit" value="Rechercher">
                    </form>

                    <table>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                        <?php foreach ($utilisateurs as $utilisateur) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($utilisateur['nom_eleve'] . ' ' . $utilisateur['prenom_eleve']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($utilisateur['mail_eleve']); ?></td>
                                <td><?php echo htmlspecialchars($utilisateur['roles_p']); ?></td>
                                <td>
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <input type="hidden" name="utilisateur_id"
                                            value="<?php echo $utilisateur['id_eleve']; ?>">
                                        <input type="hidden" name="action" value="changer_role">
                                        <input type="radio" name="nouveau_role" value="1"> Utilisateur
                                        <input type="radio" name="nouveau_role" value="2"> Tuteur ou Professeur
                                        <input type="radio" name="nouveau_role" value="3" <?php echo (isset
                                        ($_SESSION['role']) && $_SESSION['role'] == 4 ? '' : 'enabled'); ?>> Admin
                                        <input type="radio" name="nouveau_role" value="4" <?php echo (isset($_SESSION['role']) && $_SESSION['role'] == 4 ? '' : 'disabled'); ?>>
                                        Superadmin

                                        <input type="submit" value="Modifier rôle">
                                    </form>
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <input type="hidden" name="utilisateur_id"
                                            value="<?php echo $utilisateur['id_eleve']; ?>">
                                        <input type="hidden" name="action" value="modifier_mot_de_passe">
                                        Nouveau mot de passe: <input type="password" name="nouveau_mot_de_passe">
                                        <input type="submit" value="Modifier mot de passe">
                                    </form>
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <input type="hidden" name="utilisateur_id"
                                            value="<?php echo $utilisateur['id_eleve']; ?>">
                                        <input type="hidden" name="action" value="supprimer_utilisateur">
                                        <input type="submit" value="Supprimer utilisateur">
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <div class="footer-clean bg-dark">
    <footer>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-4 col-md-3 item">
                    <h3>Accès rapide</h3>
                    <ul>
                        <li><a href="./etudiant/entreprise.php">Entreprises</a></li>
                        <li><a href="./etudiant/stages.php">Stages</a></li>
                        <!--<li><a href="test.php">Rôles</a></li>-->
                        <li><a href="./admin/test.php">Admin Mode</a></li>
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

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>

</html>