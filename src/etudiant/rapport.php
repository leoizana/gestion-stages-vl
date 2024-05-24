<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rédiger un rapport</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/commands.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
   

    <style>
.classe-specifique {
    font-family: 'courier', sans-serif;
}
        /* Style de base pour le formulaire */
        .form-container {
            width: 500px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: 'Poppins', sans-serif;
        }

        .form-container h3 {
            text-align: center;
            font-size: 24px;
            color: #333;
        }

        .form-container label {
            color: #333;
            font-weight: 500;
            display: block;
            margin-top: 15px;
        }

        .form-container input,
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background: #444;
        }
        h3, label {
    color: white !important;
}

    </style>
</head>
<body>
<div class="navbar navbar-expand-md bg-dark navbar-dark">
    <div class="container">
    <a href="/./index.php" class="navbar-brand text-info">Gestion Stage</a>


        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu"><span
                class="navbar-toggler-icon"></span></button>

        <div class="collapse navbar-collapse" id="mainmenu">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a href="/./index.php" class="nav-link">Accueil</a></li>
                <li class="nav-item"><a href="entreprise.php" class="nav-link">Entreprise</a></li>
                <li class="nav-item"><a href="stages.php" class="nav-link">Stages</a></li>
                <li class="nav-item"><a href="conventions.php" class="nav-link">Conventions</a></li>
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
    <div class="form-container">
    <form action="/./send_report.php" method="POST" enctype="multipart/form-data">
            <h3>Rédiger un rapport</h3>

            <label for="objet">Objet :</label>
            <input class="classe-specifique" type="text" id="objet" name="objet" placeholder="Objet du rapport" required>

            <label pour="email">E-mail du destinataire :</label>
            <input class="classe-specifique" type="email" id="email" name="email" placeholder="Destinataire" required>

            <label pour="texte">Votre rapport :</label>
            <textarea class="classe-specifique" id="texte" name="texte" rows="8" placeholder="Rédigez votre rapport ici..." required></textarea>
            
            <label for="pdf-file">Sélectionner un fichier PDF :</label>
            <input type="file" id="pdf-file" name="pdf-file" accept="application/pdf" required>

            <button type="submit">Envoyer le rapport</button>
            </form>
    </div>
    
</body>
</html>
