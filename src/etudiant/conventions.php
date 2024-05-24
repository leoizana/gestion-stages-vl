<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
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
    <title>Déposer votre convention de stage</title>
<style>

</style>
</head>
<body>
<div class="navbar navbar-expand-md bg-dark navbar-dark">
    <div class="container">
    <a href="/./index.php" class="navbar-brand text-info">Gestion Stage</a>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu"><span
                class="navbar-toggler-icon"></span></button>

        <div class="collapse navbar-collapse" id="mainmenu">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a href="/./index.php" class="nav-link">Accueil</a></li>
                <li class="nav-item"><a href="entreprise.php" class="nav-link">Entreprise</a></li>
                <li class="nav-item"><a href="stages.php" class="nav-link">Stages</a></li>
                <li class="nav-item"><a href="rapport.php" class="nav-link">Rédiger</a></li>
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
</div>
<div class="container">
    <section style="margin-top: 9%;">
        <div class="row">
            <div class="col-sm-6 my-1">
                <h1>Déposer et envoyer un fichier PDF</h1>
            </div>
        </div>
</div>
<form style="margin-top: 10%;" align=center action="upload.php" method="post" enctype="multipart/form-data">
    <label for="pdf-file">Sélectionner un fichier PDF :</label>
    <input type="file" id="pdf-file" name="pdf-file" accept="application/pdf" required>
    <button  class="btn btn-outline-info" type="submit">Envoyer</button>
</form>   
<div class="footer-clean bg-dark">
            
<footer style="margin-top:15%;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-4 col-md-3 item">
                <h3>Accès rapide</h3>
                <ul>
                    <li><a href="./pages/entreprise.html">Entreprises</a></li>
                    <li><a href="./pages/stages.html">Stages</a></li>
                    <!--<li><a href="test.php">Rôles</a></li>-->
                    <li><a href="/admin/test.php">Admin Mode</a></li>
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
</body>
</html>
