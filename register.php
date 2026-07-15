<?php
include("config/database.php");
$message = "";
if(isset($_POST['register']))
{
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
    $numero_client = mysqli_real_escape_string($conn, $_POST['numero_client']);
    $mot_de_passe = $_POST['mot_de_passe'];
    $confirm_password = $_POST['confirm_password'];
    if($mot_de_passe != $confirm_password)
    {
        $message = "Les mots de passe ne correspondent pas.";
    }
    else
    {
        $mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        $check = mysqli_query(
            $conn,
            "SELECT * FROM utilisateurs
            WHERE email='$email'"
        );
        if(mysqli_num_rows($check) > 0)
        {
            $message = "Cet email existe déjà.";
        }
        else
        {
            $sql = "INSERT INTO utilisateurs
            (
                nom,
                prenom,
                email,
                telephone,
                numero_client,
                mot_de_passe
            )
            VALUES
            (
                '$nom',
                '$prenom',
                '$email',
                '$telephone',
                '$numero_client',
                '$mot_de_passe'
            )";
if(mysqli_query($conn,$sql))
{
    $id_utilisateur = mysqli_insert_id($conn);
    $numero_compte = "RB-".rand(100000,999999);
    mysqli_query($conn,"
    INSERT INTO comptes
    (
        numero_compte,
        type_compte,
        solde,
        id_utilisateur,
        statut_compte
    )
    VALUES
    (
        '$numero_compte',
        'Courant',
        '2450',
        '$id_utilisateur',
        'Actif'
    )");
    $id_compte = mysqli_insert_id($conn);
    $numero_carte = "4567".rand(1000,9999).rand(1000,9999).rand(1000,9999);
    $date_expiration = date("Y-m-d",strtotime("+3 years"));
    $cvv = rand(100,999);
    mysqli_query($conn,"
    INSERT INTO cartes_bancaires
    (
        numero_carte,
        type_carte,
        date_expiration,
        id_compte,
        statut_carte,
        cvv
    )
    VALUES
    (
        '$numero_carte',
        'Visa Gold',
        '$date_expiration',
        '$id_compte',
        'Active',
        '$cvv'
    )");
    mysqli_query($conn,"
    INSERT INTO conversations
    (
        id_utilisateur
    )
    VALUES
    (
        '$id_utilisateur'
    )");
    mysqli_query($conn,"
    INSERT INTO transactions
    (id_utilisateur,operation,montant,date_operation)
    VALUES
    ('$id_utilisateur','Dépôt initial','2450',NOW())");
    mysqli_query($conn,"
    INSERT INTO transactions
    (id_utilisateur,operation,montant,date_operation)
    VALUES
    ('$id_utilisateur','Paiement Canal+','-35',NOW())");
    mysqli_query($conn,"
    INSERT INTO transactions
    (id_utilisateur,operation,montant,date_operation)
    VALUES
    ('$id_utilisateur','Retrait GAB','-120',NOW())");
    mysqli_query($conn,"
    INSERT INTO transactions
    (id_utilisateur,operation,montant,date_operation)
    VALUES
    ('$id_utilisateur','Réception salaire','850',NOW())");
    header("Location: login.php?success=1");
    exit();
}
else
{
    $message = "Erreur lors de l'inscription.";
}
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta
name="viewport"
content="width=device-width, initial-scale=1.0">
<title>
Inscription - RawBank Assist
</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="register-container">
    <div class="register-header">
        <div class="logo">
            <img src="images/rawbank-logo.png" alt="RawBank">
        </div>
        <div class="header-text">
            <h2>Inscription client</h2>
        </div>
    </div>
    <?php if($message!=""): ?>
        <div class="error-message">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <form method="POST">
<div class="row">
<div class="col">
<label>Nom</label>
<input
type="text"
name="nom"
placeholder="Votre nom"
required>
</div>
<div class="col">
<label>Prénom</label>
<input
type="text"
name="prenom"
placeholder="Votre prénom"
required>
</div>
</div>
<label>Email</label>
<input
type="email"
name="email"
placeholder="Adresse email"
required>
<label>Téléphone</label>
<input
type="text"
name="telephone"
placeholder="+243..."
required>
<label>N° client RawBank </label>
<input
type="text"
name="numero_client"
placeholder="RB-000001"
required>
<label>Mot de passe</label>
<div class="password-box">
<input
type="password"
id="password"
name="mot_de_passe"
placeholder="Mot de passe"
required>
<span id="togglePassword">
<svg id="eyeOpen1" width="20" height="20"
xmlns="http://www.w3.org/2000/svg"
fill="none"
viewBox="0 0 24 24"
stroke="currentColor"
stroke-width="1.5">
<path stroke-linecap="round"
stroke-linejoin="round"
d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5s8.577 3.01 9.964 7.183a1.01 1.01 0 0 1 0 .639C20.577 16.49 16.64 19.5 12 19.5S3.423 16.49 2.036 12.322ZM12 15.75A3.75 3.75 0 1 0 12 8.25a3.75 3.75 0 0 0 0 7.5"/>
</svg>
<svg id="eyeClose1"
width="20"
height="20"
style="display:none;"
xmlns="http://www.w3.org/2000/svg"
fill="none"
viewBox="0 0 24 24"
stroke="currentColor"
stroke-width="1.5">
<path stroke-linecap="round"
stroke-linejoin="round"
d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"/>
</svg>
</span>
</div>
<label>Confirmer le mot de passe</label>
<div class="password-box">
    <input
        type="password"
        id="confirm_password"
        name="confirm_password"
        placeholder="Confirmer le mot de passe"
        required>
    <span id="toggleConfirmPassword">
        <svg id="eyeOpen2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5s8.577 3.01 9.964 7.183a1.01 1.01 0 0 1 0 .639C20.577 16.49 16.64 19.5 12 19.5S3.423 16.49 2.036 12.322ZM12 15.75A3.75 3.75 0 1 0 12 8.25a3.75 3.75 0 0 0 0 7.5"/>
        </svg>
        <svg id="eyeClose2" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.477 10.488A3 3 0 0 0 13.5 13.5M9.88 9.88A3 3 0 0 1 14.12 14.12M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.52 10.52 0 0 1-4.293 5.774A10.45 10.45 0 0 1 12 19.5c-4.756 0-8.773-3.162-10.065-7.498a10.52 10.52 0 0 1 4.293-5.774"/>
        </svg>
    </span>
</div>
<button
type="submit"
name="register">
Créer mon compte
</button>
        <p class="login-link">
            Déjà inscrit ?
            <a href="login.php">
                Accéder à l'assistant
            </a>
        </p>
    </form>
</div>
<script>
const password = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");
const eyeOpen1 = document.getElementById("eyeOpen1");
const eyeClose1 = document.getElementById("eyeClose1");
togglePassword.onclick = function () {
    if (password.type === "password") {
        password.type = "text";
        eyeOpen1.style.display = "none";
        eyeClose1.style.display = "block";
    } else {
        password.type = "password";
        eyeOpen1.style.display = "block";
        eyeClose1.style.display = "none";
    }
};
const confirmPassword = document.getElementById("confirm_password");
const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
const eyeOpen2 = document.getElementById("eyeOpen2");
const eyeClose2 = document.getElementById("eyeClose2");
toggleConfirmPassword.onclick = function () {
    if (confirmPassword.type === "password") {
        confirmPassword.type = "text";
        eyeOpen2.style.display = "none";
        eyeClose2.style.display = "block";
    } else {
        confirmPassword.type = "password";
        eyeOpen2.style.display = "block";
        eyeClose2.style.display = "none";
    }
};
</script>
</body>
</html>