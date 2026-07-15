<?php
$message_success = "";
if(isset($_POST['envoyer']))
{
    $message = trim($_POST['message']);
    if(!empty($message))
    {
        $message_success =
        "Votre message a été envoyé au conseiller avec succès.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Conseiller RawBank</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="register-container">
    <h2> Conseiller Clientèle</h2>
    <div class="conseiller-box">
        <p><strong>Nom :</strong> Conseiller RawBank</p>
        <p><strong>Email :</strong> support@rawbank.cd</p>
        <p><strong>Téléphone :</strong> +243 819 707 241</p>
    </div>
    <form method="POST">
        <textarea
            name="message"
            placeholder="Décrivez votre problème ou votre demande..."
            rows="5"
            required></textarea>
        <button type="submit" name="envoyer">
            Envoyer un message
        </button>
    </form>
    <?php if($message_success != ""): ?>
    <div class="success-message">
        <?php echo $message_success; ?>
    </div>
    <?php endif; ?>
    <br>
    <a href="chatbot.php" class="btn-secondary">
        Retour au chatbot
    </a>
</div>
</body>
</html>