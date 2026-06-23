<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RawBank Assist</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
<div class="logo">

<a href="index.php">
    <img src="images/rawbank-logo.png" alt="RawBank">
</a>

<div>
    <h1>RawBank Assist</h1>
    <p class="subtitle">Assistant Bancaire Intelligent</p>
</div>

</div>

    <nav>
        <a href="index.php">Accueil</a>
        <a href="chatbot.php">Chatbot</a>
        <a href="login.php">Connexion</a>
    </nav>
</header>
<section class="hero">

    <h2>
        Automatisation du service client bancaire
        grâce aux chatbots intelligents
    </h2>
    <p>
        Votre assistant bancaire intelligent
        disponible 24h/24 et 7/7jours.
    </p>
    <div class="buttons">
        <a href="register.php" class="btn-primary">
            Créer un compte
        </a>
        <a href="login.php" class="btn-secondary">
            Se connecter
        </a>
    </div>

</section>
<section class="features">

    <div class="card">
        <h3>Assistance 24/7</h3>
        <p>
            Réponses instantanées aux questions
            des clients à tout moment.
        </p>
    </div>

    <div class="card">
        <h3>Sécurité</h3>
        <p>
            Protection des données et
            authentification sécurisée.
        </p>
    </div>
    <div class="card">
        <h3>Intelligence</h3>
        <p>
            Compréhension du langage naturel
            et assistance intelligente.
        </p>
    </div>
</section>
<footer>
    <p>
        © 2026 RawBank Assist | Développé par Ir Didier Nsim
    </p>
</footer>
</body>
<script>
const cards = document.querySelectorAll('.card');
cards.forEach(card => {
    card.addEventListener('click', () => {
        cards.forEach(c => c.classList.remove('active-card'));
        card.classList.add('active-card');
    });
});

</script>
</html>
