<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="register-container">

<h2>
Bienvenue
<?php echo $_SESSION['nom']; ?>
👋
</h2>

<p>
Connexion réussie à RawBank Assist.
</p>

<div class="dashboard-actions">

    <a href="chatbot.php" class="btn-primary">
        Accéder au chatbot
    </a>
</div>
<br><br>
<a href="logout.php" class="btn-secondary">
Déconnexion
</a>
</div>
</body>
</html>