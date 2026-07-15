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
    <span id="togglePassword">
<svg id="eyeOpen" width="20" height="20"
     xmlns="http://www.w3.org/2000/svg"
     fill="none"
     viewBox="0 0 24 24"
     stroke-width="1.5"
     stroke="currentColor">
    <path stroke-linecap="round"
          stroke-linejoin="round"
          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5s8.577 3.01 9.964 7.183a1.01 1.01 0 0 1 0 .639C20.577 16.49 16.64 19.5 12 19.5S3.423 16.49 2.036 12.322ZM12 15.75A3.75 3.75 0 1 0 12 8.25a3.75 3.75 0 0 0 0 7.5"/>
</svg>
<svg id="eyeClose" width="20" height="20"
     style="display:none;"
     xmlns="http://www.w3.org/2000/svg"
     fill="none"
     viewBox="0 0 24 24"
     stroke-width="1.5"
     stroke="currentColor">
    <path stroke-linecap="round"
          stroke-linejoin="round"
          d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
</svg>
</span>
</div>
        <button type="submit" name="login">
            Se connecter
        </button>
    </form>
</div>
<script>
const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");
const eyeOpen = document.getElementById("eyeOpen");
const eyeClose = document.getElementById("eyeClose");
togglePassword.addEventListener("click", function () {
    if (password.type === "password") {
        password.type = "text";
        eyeOpen.style.display = "none";
        eyeClose.style.display = "block";
    } else {
        password.type = "password";
        eyeOpen.style.display = "block";
        eyeClose.style.display = "none";
    }

});
</script>
</body>
</html>