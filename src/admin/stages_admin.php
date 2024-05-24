<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">
<link href="../css/commands.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<link rel="shortcut icon" href="../img/" type="image/x-icon">
<title>Stages - Gestion Stage</title>
<style>
.barre {
    display: flex;
    align-items: center;
    gap: 20px;
}
.btn {
    flex-shrink: 0;
    padding: 10px 20px;
}
td, th {
    color: white;
}
</style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar navbar-expand-md bg-dark navbar-dark">
        <div class="container">
            <a href="/./index.php" class="navbar-brand text-info">Gestion Stage</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainmenu">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a href="_admin_index.php" class="nav-link">Accueil</a></li>
                    <li class="nav-item"><a href="entreprise_admin.php" class="nav-link">Entreprise</a></li>
                    <li class="nav-item"><a href="manage_user.php" class="nav-link">Gestion des utilisateurs</a></li>
                </ul>
                <?php
                if (isset($_SESSION['utilisateur_connecte']) && $_SESSION['utilisateur_connecte'] === true) {
                    echo '<li class="navbar-nav nav-item"><a href="/./logout.php" class="btn btn-outline-danger">Se déconnecter</a></li>';
                    echo '<li class="navbar-nav nav-item"><a href="" class="btn btn-outline-info">' . $_SESSION['prenom'] . '</a></li>';
                } else {
                    echo '<li class="navbar-nav nav-item"><a href="/./page_connexion.html" class="btn btn-outline-info">Se connecter</a></li>';
                }
                ?>
            </div>
        </div>
    </div>
    <div class="container">
        <section style="margin-top: 9%;">
            <div class="row">
                <div class="col-sm-6 my-1">
                    <h2 class="fw-bold">Liste des Stages</h2>
                    <p class="command-descreption">Trouvez les stages de vos étudiants plus facilement !</p>
                    <div class="barre">
                        <a href="/./etudiant/insertion_stages.html" class="btn btn-outline-info">Insérer stage</a>
                        <!-- Barre de recherche -->
                        <input type="text" id="search-bar" placeholder="Rechercher..." onkeyup="filterTable()">
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Tableau des stages -->
    <table class="table table-striped" id="stages-table">
        <thead>
            <tr>
                <th>Classe</th>
                <th>Date de début</th>
                <th>Date de fin</th>
                <th>Session</th>
                <th>Thèmes</th>
                <th>Commentaires</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $serveur = "127.0.0.1";
            $utilisateur = "root";
            $mot_de_passe = "root";
            $base_de_donnees = "stages";
            try {
                $dbh = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $dbh->prepare("SELECT id_stage, classe_eleve, date_debut, date_fin, session, themes, commentaires FROM stage");
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["classe_eleve"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["date_debut"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["date_fin"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["session"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["themes"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["commentaires"]) . "</td>";
                    echo "<td>";
                    echo "<a href='/./prof/modifier.php?id_stage=" . htmlspecialchars($row["id_stage"]) . "' class='btn btn-outline-info'>Modifier</a>";
                    echo " ";
                    echo "<a href='supprimer.php?id_stage=" . htmlspecialchars($row["id_stage"]) . "' class='btn btn-outline-danger' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet élément?\");'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                echo "Erreur : " . htmlspecialchars($e->getMessage());
            }
            $dbh = null;
            ?>
        </tbody>
    </table>
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
    <script>
    function filterTable() {
        const input = document.getElementById("search-bar");
        const filter = input.value.toLowerCase();
        const table = document.getElementById("stages-table");
        const rows = table.getElementsByTagName("tr");
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const cells = row.getElementsByTagName("td");
            let found = false;
            for (let j = 0; j < cells.length; j++) {
                if (cells[j].innerText.toLowerCase().includes(filter)) {
                    found = true;
                    break;
                }
            }
            row.style.display = found ? "" : "none";
        }
    }
    </script>
</body>
</html>
