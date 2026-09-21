<?php
session_start();
include('connexion.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {

        $stmt = $pdo->prepare("
            SELECT * FROM utilisateurs
            WHERE email = :email OR telephone = :email
        ");

        $stmt->execute([
            'email' => $email
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user['mot_de_passe']) {

            $_SESSION['user_id'] = $user['id_utilisateur'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_role'] = $user['role'];

            header("Location: profile.php");
            exit();

        } else {

            $error = "Email/Téléphone ou mot de passe incorrect.";
        }

    } else {

        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion - Evently</title>
    <link rel="stylesheet" href="aaa.css">
</head>

<body>

<header class="navbar">

    <a href="index.php" class="logo">
        Evently
    </a>

    <div class="nav-buttons">

        <a href="Cox.php" class="btnconnecter">
            Se connecter
        </a>

        <a href="inscription.php" class="btnsinscrire">
            S'inscrire
        </a>

    </div>

</header>


<main class="main">

    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">

            <div class="badge">
                Evently
            </div>

            <p class="texte">
                Réservez · Organisez · Célébrez
            </p>

            <h1 class="hero-title">
                Votre événement,<br>
                <span>notre priorité</span>
            </h1>

            <p class="hero-description">
                Trouvez la date parfaite pour votre événement
                parmi une large sélection de lieux et de créneaux
                disponibles.
            </p>

            <div class="feature-item">
                <div class="feature-icon">✓</div>
                <div>
                    <div class="feature-title">
                        Large choix d'événements
                    </div>

                    <div class="feature-desc">
                        Mariages, anniversaires, séminaires, ateliers...
                    </div>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">✓</div>
                <div>
                    <div class="feature-title">
                        Réservation simple et rapide
                    </div>

                    <div class="feature-desc">
                        En quelques clics seulement
                    </div>
                </div>
            </div>

            <div class="feature-item">
                <div class="feature-icon">✓</div>
                <div>
                    <div class="feature-title">
                        Suivi en temps réel
                    </div>

                    <div class="feature-desc">
                        Recevez des notifications sur vos réservations
                    </div>
                </div>
            </div>

        </div>

    </section>


    <section class="form">

        <div class="login-card">

            <div class="card-line"></div>

            <h2>Connexion</h2>

            <p class="subtitle">
                Accédez à votre compte pour profiter de toutes
                les fonctionnalités de notre plateforme.
            </p>


            <?php if (!empty($error)): ?>

                <p class="error">
                    <?= htmlspecialchars($error) ?>
                </p>

            <?php endif; ?>


            <form method="POST">

                <div class="form-group">

                    <label for="email">
                        Email ou numéro de téléphone
                    </label>

                    <div class="input-box">

                        <input
                            type="text"
                            name="email"
                            id="email"
                            placeholder="exemple@gmail.com"
                            required
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label for="password">
                        Mot de passe
                    </label>

                    <div class="input-box">

                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Entrez votre mot de passe"
                            required
                        >

                    </div>

                </div>


                <div class="form-options">

                    <label>
                        <input type="checkbox" name="remember">
                        Se souvenir de moi
                    </label>

                    <a href="forgot_password.php">
                        Mot de passe oublié ?
                    </a>

                </div>


                <button type="submit" class="submit">
                    Se connecter
                </button>


                <div class="divider">
                    <span>Ou</span>
                </div>


                <a href="#" class="google">
                    Continuer avec Google
                </a>


                <p class="text">
                    Vous n'avez pas de compte ?
                    <a href="inscription.php">
                        S'inscrire
                    </a>
                </p>

            </form>

        </div>

    </section>

</main>

</body>
</html>