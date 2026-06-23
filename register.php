<?php

include("config/database.php");

$message = "";

if(isset($_POST['register']))
{
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    $check = mysqli_query(
        $conn,
        "SELECT * FROM utilisateurs WHERE email='$email'"
    );

    if(mysqli_num_rows($check) > 0)
    {
        $message = "Cet email existe déjà.";
    }
    else
    {
        $sql = "INSERT INTO utilisateurs
        (nom, prenom, email, telephone, mot_de_passe)
        VALUES
        ('$nom','$prenom','$email','$telephone','$mot_de_passe')";

        if(mysqli_query($conn, $sql))
        {
            header("Location: login.php");
            exit();
        }
        else
        {
            $message = "Erreur lors de l'inscription.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - RawBank Assist</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="register-container">

    <h2>Créer un compte</h2>

    <?php if($message != ""): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST">

        <input type="text" name="nom" placeholder="Nom" required>

        <input type="text" name="prenom" placeholder="Prénom" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="text" name="telephone" placeholder="Téléphone" required>

        <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>

        <button type="submit" name="register">
            Créer mon compte
        </button>

    </form>

</div>

</body>
</html>