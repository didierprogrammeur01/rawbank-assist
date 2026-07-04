<?php
session_start();
include("config/database.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$nom = $_SESSION['nom'];

$sql = mysqli_query($conn,"
SELECT id_conversation
FROM conversations
WHERE id_utilisateur='$user_id'
LIMIT 1");

if(mysqli_num_rows($sql)==0)
{
    mysqli_query($conn,"
    INSERT INTO conversations(id_utilisateur)
    VALUES('$user_id')");

    $conversation = mysqli_insert_id($conn);
}
else
{
    $row = mysqli_fetch_assoc($sql);
    $conversation = $row['id_conversation'];
}

$messages = mysqli_query($conn,"
SELECT *
FROM messages
WHERE id_conversation='$conversation'
ORDER BY id_message ASC");
?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Chatbot</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="chat-container">

    <div class="chat-header">

        <div class="chat-user">

            <div class="bot-avatar">🤖</div>

            <div>

                <h2>Chatbot</h2>

                <span class="online">🟢 En ligne</span>

            </div>

        </div>

    </div>

    <div class="chat-body" id="chatMessages">

<?php

if(mysqli_num_rows($messages)==0)
{
?>

<div class="message bot">

    <div class="bubble">

        <strong>Bonjour <?php echo $nom; ?> 👋</strong>

        <br><br>

        Je suis <b>RawBank Assist</b>.

        <br><br>

        Comment puis-je vous aider aujourd'hui ?

    </div>

</div>

<div id="quick-actions">

<button class="quick-btn"
onclick="envoyerQuestion('Quel est mon solde ?')">

💰 Solde

</button>

<button class="quick-btn"
onclick="envoyerQuestion('Afficher ma carte bancaire')">

💳 Carte

</button>

<button class="quick-btn"
onclick="envoyerQuestion('Voir mon historique')">

📋 Historique

</button>

</div>

<?php
}
else
{

while($msg=mysqli_fetch_assoc($messages))
{

if($msg['expediteur']=="client")
{
?>

<div class="message user">

<div class="bubble">

<?php echo nl2br($msg['contenu']); ?>

</div>

</div>

<?php
}
else
{
?>

<div class="message bot">

<div class="bubble">

<?php echo nl2br($msg['contenu']); ?>

</div>

</div>

<?php
}

}

}

?>

    </div>

    <form id="chatForm">

        <input
        type="text"
        id="messageInput"
        placeholder="Écrivez votre message..."
        autocomplete="off"
        required>

        <button type="submit">

        📨

        </button>

    </form>

    <a href="conseiller.php" class="advisor-btn">

    👨‍💼 Parler à un conseiller

    </a>

    <a href="dashboard.php" class="btn-primary">

    ← Retour au Dashboard

    </a>

</div>

<script src="chatbot.js"></script>

</body>
</html>