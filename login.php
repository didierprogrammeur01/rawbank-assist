<?php
session_start();
include("config/database.php");

$message = "";

if(isset($_POST['login']))
{
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    $sql = mysqli_query(
        $conn,
        "SELECT * FROM utilisateurs WHERE email='$email'"
    );

    if(mysqli_num_rows($sql) > 0)
    {
        $user = mysqli_fetch_assoc($sql);

        if(password_verify($mot_de_passe, $user['mot_de_passe']))
        {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];

            header("Location: chatbot.php");
            exit();
        }
        else
        {
            $message = "Mot de passe incorrect.";
        }
    }
    else
    {
        $message = "Utilisateur introuvable.";
    }
}
?>
<?php if(isset($_GET['success'])): ?>
<div class="success-message">
    Compte créé avec succès 
</div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion - RawBank Assist</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="register-container">

    <h2>Connexion</h2>

    <?php if($message != ""): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">

        <input type="email"
               name="email"
               placeholder="Adresse email"
               required>

               <div class="password-box">

<input
    type="password"
    id="password"
    name="mot_de_passe"
    placeholder="Mot de passe"
    required>

<span id="togglePassword">👁️</span>

</div>

        <button type="submit" name="login">
            Se connecter
        </button>

    </form>
</div>
<script>

const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

togglePassword.addEventListener("click", function(){

    if(password.type === "password"){
        password.type = "text";
        this.innerHTML = "🙈";
    }else{
        password.type = "password";
        this.innerHTML = "👁️";
    }

});

</script>
</body>
</html>