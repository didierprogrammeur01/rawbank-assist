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
    // ID du nouvel utilisateur
    $id_utilisateur = mysqli_insert_id($conn);

    // Création du compte bancaire
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

    // ID du compte créé
    $id_compte = mysqli_insert_id($conn);

    // Création de la carte bancaire
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

    // Création de la conversation
    mysqli_query($conn,"
    INSERT INTO conversations
    (
        id_utilisateur
    )
    VALUES
    (
        '$id_utilisateur'
    )");

    // Transactions de démonstration
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

<span id="togglePassword">👁️</span>

</div>

<label>Confirmer le mot de passe</label>

<div class="password-box">

<input
type="password"
id="confirm_password"
name="confirm_password"
placeholder="Confirmer le mot de passe"
required>

<span id="toggleConfirmPassword">👁️</span>

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

const togglePassword =
document.getElementById("togglePassword");

const password =
document.getElementById("password");

togglePassword.addEventListener("click", function(){

    if(password.type === "password")
    {
        password.type = "text";
        this.innerHTML = "🙈";
    }
    else
    {
        password.type = "password";
        this.innerHTML = "👁️";
    }

});

const toggleConfirmPassword =
document.getElementById("toggleConfirmPassword");

const confirmPassword =
document.getElementById("confirm_password");

toggleConfirmPassword.addEventListener("click", function(){

    if(confirmPassword.type === "password")
    {
        confirmPassword.type = "text";
        this.innerHTML = "🙈";
    }
    else
    {
        confirmPassword.type = "password";
        this.innerHTML = "👁️";
    }

});
</script>
</body>
</html>