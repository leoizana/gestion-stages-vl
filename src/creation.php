<?php

$serveur = "127.0.0.1"; 
$utilisateur = "root"; 
$mot_de_passe = "root"; 
$base_de_donnees = "stages"; 

try {

    $dbh = new PDO("mysql:host=$serveur;dbname=$base_de_donnees;charset=utf8", $utilisateur, $mot_de_passe);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

$stmt = $dbh->prepare("INSERT INTO eleve (mdp, nom_eleve, prenom_eleve, mail_eleve, roles_p) VALUES (:mdp, :nom_eleve, :prenom_eleve, :mail_eleve, 1)"); // Ajout de la valeur par défaut du rôle (1)

$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$mail = $_POST['email'];
$mot_de_passe = $_POST['mdp'];

$mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

$stmt->bindParam(':mdp', $mot_de_passe_hash);
$stmt->bindParam(':nom_eleve', $nom);
$stmt->bindParam(':prenom_eleve', $prenom);
$stmt->bindParam(':mail_eleve', $mail);

try {

    $stmt->execute();
    echo "Création du compte réussie !";
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$dbh = null;

session_start();
$_SESSION['prenom'] = $prenom;

// Redirection vers la nouvelle page
header("Location: index.php");

?>
