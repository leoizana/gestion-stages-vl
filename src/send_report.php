<?php
require 'vendor/autoload.php'; // Assurez-vous que PHPMailer est installé via Composer
require '.env';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;
/*
$_ENV['SMTP_HOST'] = $host;
$_ENV['SMTP_PORT'] = $port;
$_ENV['SMTP_USERNAME'] = $username;
$_ENV['SMTP_PASSWORD'] = $password;
*/





// Chargement des variables d'environnement depuis le fichier serveurmail.env
$dotenv = Dotenv::createImmutable(__DIR__, '.env');
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $objet = trim($_POST['objet']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $texte = trim($_POST['texte']);

    // Vérifiez si un fichier PDF a été téléchargé
    if (isset($_FILES['pdf-file']) && $_FILES['pdf-file']['error'] === UPLOAD_ERR_OK) {
        $pdf_tmp_name = $_FILES['pdf-file']['tmp_name'];
        $pdf_name = $_FILES['pdf-file']['name'];

        // Créez une nouvelle instance de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuration de PHPMailer pour utiliser SMTP
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST']; // Serveur SMTP
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USERNAME']; // Nom d'utilisateur SMTP
            $mail->Password = $_ENV['SMTP_PASSWORD']; // Mot de passe SMTP
            $mail->SMTPSecure = 'ssl'; // Utilisez 'tls' ou 'ssl'
            $mail->Port = $_ENV['SMTP_PORT']; // Port SMTP

            // Paramètres de l'e-mail
            $mail->setFrom($_ENV['SMTP_USERNAME'], 'Gestion Stage'); // Adresse de l'expéditeur
            $mail->addAddress($email); // Destinataire
            $mail->Subject = $objet; // Sujet de l'e-mail
            $mail->Body = "Rapport :\n\n" . $texte; // Contenu du rapport
            
            // Ajouter le fichier PDF en tant que pièce jointe
            $mail->addAttachment($pdf_tmp_name, $pdf_name);

            // Envoyer l'e-mail
            $mail->send();
            echo "Rapport envoyé avec succès à $email.";
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'e-mail : " . $mail->ErrorInfo;
        }
    } else {
        echo "Le fichier PDF n'est pas valide.";
    }
} else {
    echo "Méthode de requête non autorisée.";
}

// Redirection vers une autre page
header("Location: index.php");
exit;