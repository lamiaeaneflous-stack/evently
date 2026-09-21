<?php

include("connexion.php");

$message = "";

if (isset($_GET["success"])) {
    $message = "Inscription réussie !";
}

if (isset($_POST["inscrire"])) {

    $nom = trim($_POST["nom"] ?? '');
    $prenom = trim($_POST["prenom"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $telephone = trim($_POST["telephone"] ?? '');
    $mot_de_passe = $_POST["mot_de_passe"] ?? '';
    $role = "utilisateur";

    $check = $pdo->prepare("
        SELECT id_utilisateur
        FROM utilisateurs
        WHERE email = ?
    ");

    $check->execute([$email]);

    if ($check->fetch()) {

        $message = "Cet email est déjà utilisé.";

    } else {

        $mot_de_passe_hash = password_hash(
            $mot_de_passe,
            PASSWORD_DEFAULT
        );

        $sql = "
            INSERT INTO utilisateurs
            (
                nom,
                prenom,
                email,
                telephone,
                mot_de_passe,
                role
            )
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $req = $pdo->prepare($sql);

        if ($req->execute([
            $nom,
            $prenom,
            $email,
            $telephone,
            $mot_de_passe_hash,
            $role
        ])) {

            header("Location: index.php");
            exit();

        } else {

            $message = "Erreur lors de l'inscription.";

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
        content="width=device-width, initial-scale=1.0"
    >

    <title>Evently - Inscription</title>

    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --dark-green: #195448;
    --green: #286b5c;
    --light-green: #dcebe6;
    --very-light-green: #edf5f2;
    --background: #f5f7f6;
    --border: #d8dfdd;
    --text: #183b3b;
    --gray: #697778;
}

body {
    font-family: "DM Sans", sans-serif;
    background: #f5f7f6;
    color: #183b3b;
}


.navbar {
    height: 74px;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 5.2%;
    border-bottom: 1px solid #e3e7e5;
}


.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 25px;
    font-weight: 700;
    color: #173d3c;
    text-decoration: none;
}


.logo-icon {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
}


.logo-icon svg {
    width: 30px;
    height: 30px;
    stroke-width: 2;
}


nav {
    display: flex;
    gap: 42px;
    margin-left: 50px;
}


nav a,
.nav-buttons a {
    text-decoration: none;
    color: #526060;
    font-size: 14px;
    font-weight: 500;
}


nav a:hover,
.login:hover {
    color: var(--green);
}


.nav-buttons {
    display: flex;
    align-items: center;
    gap: 32px;
}


.btnconnecter {
    transition: 0.2s;
}


.btnconnecter:hover {
    color: var(--green) !important;
}


.signup {
    background: var(--dark-green);
    color: white !important;
    padding: 12px 27px;
    border-radius: 8px;
    transition: 0.2s;
}


.signup:hover {
    background: #123f36;
}


.register-page {
    min-height: calc(100vh - 74px);
    display: grid;
    grid-template-columns: 50% 50%;
}


.left-side {
    position: relative;
    min-height: calc(100vh - 74px);
    background-image: url("images/table decor.jpg");
    background-size: cover;
    background-position: center;
}


.overlay {
    position: absolute;
    inset: 0;

    background:
        linear-gradient(
            90deg,
            rgba(245,247,246,0.94) 0%,
            rgba(245,247,246,0.82) 45%,
            rgba(245,247,246,0.50) 100%
        );
}


.left-side::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 95px;
    height: 190px;
    background: #c7d7d2;
    border-bottom-right-radius: 100%;
}


.left-side::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 150px;
    height: 115px;
    background: #b9cec8;
    border-top-right-radius: 100%;
}


.left-content {
    position: relative;
    z-index: 2;
    padding: 75px 9%;
    max-width: 690px;
}


.brand {
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: 34px;
    font-weight: 700;
    margin-bottom: 13px;
}


.brand svg {
    width: 43px;
    height: 43px;
    stroke-width: 1.8;
}


.subtitle {
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 22px;
}


.left-content h1 {
    font-size: 44px;
    line-height: 1.12;
    margin-bottom: 20px;
    color: #1b4443;
}


.description {
    font-size: 17px;
    line-height: 1.55;
    color: #314b4c;
    max-width: 440px;
    margin-bottom: 43px;
}


.feature {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 27px;
}


.feature-icon {
    width: 63px;
    height: 63px;
    flex-shrink: 0;
    border-radius: 50%;
    background: rgba(199, 221, 215, 0.85);
    display: flex;
    align-items: center;
    justify-content: center;
}


.feature-icon svg {
    width: 29px;
    height: 29px;
    color: #174f46;
}


.feature h3 {
    font-size: 16px;
    margin-bottom: 4px;
}


.feature p {
    font-size: 13px;
    color: #5e6e6e;
}


.slogan {
    margin-top: 57px;
    margin-left: 3px;
    font-family: cursive;
    font-size: 27px;
    line-height: 1.2;
    color: #296154;
}


.right-side {
    background: #f4f6f5;
    padding: 20px 5%;
    display: flex;
    justify-content: center;
    align-items: center;
}


.form-container {
    width: 100%;
    max-width: 685px;
    background: #ffffff;
    border: 1px solid #e0e5e3;
    border-radius: 10px;
    padding: 32px 43px 28px;
    box-shadow:
        0 5px 20px rgba(25, 60, 55, 0.04);
}


.form-small-title {
    font-size: 11px;
    letter-spacing: 1px;
    color: #58736d;
    font-weight: 600;
}


.form-container h2 {
    font-size: 36px;
    margin: 3px 0 5px;
    color: #153f3d;
}


.form-description {
    color: #697878;
    font-size: 14px;
    margin-bottom: 25px;
}


.two-columns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 17px;
}


.input-box {
    height: 59px;
    border: 1px solid #d6dfdc;
    border-radius: 8px;
    display: flex;
    align-items: center;
    padding: 0 15px;
    margin-bottom: 15px;
    transition: 0.2s;
    background: #fff;
}


.input-box:focus-within {
    border-color: #286b5c;
    box-shadow:
        0 0 0 3px rgba(40, 107, 92, 0.08);
}


.input-box > svg {
    width: 20px;
    height: 20px;
    color: #294f4d;
    flex-shrink: 0;
}


.input-box input {
    border: none;
    outline: none;
    width: 100%;
    margin-left: 15px;
    font-family: inherit;
    font-size: 14px;
    color: #244544;
}


.input-box input::placeholder {
    color: #9ba5a5;
}


.password-box {
    position: relative;
}


.password-box input {
    padding-right: 30px;
}


.conditions {
    display: flex;
    align-items: flex-start;
    gap: 9px;
    margin-top: 5px;
    color: #697778;
    font-size: 12px;
    line-height: 1.5;
    cursor: pointer;
}


.conditions input {
    width: 15px;
    height: 15px;
    margin-top: 2px;
    accent-color: #286b5c;
    flex-shrink: 0;
}


.conditions a {
    color: #286b5c;
    text-decoration: none;
    font-weight: 600;
}


.conditions a:hover {
    text-decoration: underline;
}


.btn-register {
    width: 100%;
    height: 52px;
    margin-top: 24px;
    border: none;
    border-radius: 8px;
    background: #195448;
    color: #ffffff;
    font-family: inherit;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.2px;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 5px 14px rgba(25, 84, 72, 0.12);
}


.btn-register:hover {
    background: #123f36;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(25, 84, 72, 0.18);
}


.btn-register:active {
    transform: translateY(0);
}


.message {
    background: #fff1f1;
    color: #c44d4d;
    border: 1px solid #f0d0d0;
    border-left: 4px solid #d9534f;
    border-radius: 7px;
    padding: 12px 14px;
    margin-bottom: 20px;
    font-size: 13px;
    line-height: 1.4;
}


.already-account {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    margin-top: 19px;
    color: #7a8584;
    font-size: 12px;
}


.already-account a {
    color: #286b5c;
    text-decoration: none;
    font-weight: 700;
}


.already-account a:hover {
    color: #195448;
    text-decoration: underline;
}


@media (max-width: 1100px) {

    .register-page {
        grid-template-columns: 45% 55%;
    }

    .left-content {
        padding: 55px 8%;
    }

    .left-content h1 {
        font-size: 36px;
    }

    .description {
        font-size: 15px;
    }

    .form-container {
        padding: 30px;
    }

}


@media (max-width: 850px) {

    .navbar {
        padding: 0 25px;
    }

    nav {
        gap: 20px;
        margin-left: 0;
    }

    .nav-buttons {
        gap: 15px;
    }

    .register-page {
        grid-template-columns: 1fr;
    }

    .left-side {
        display: none;
    }

    .right-side {
        min-height: calc(100vh - 74px);
        padding: 35px 20px;
    }

    .form-container {
        max-width: 600px;
    }

}


@media (max-width: 600px) {

    .navbar {
        height: auto;
        min-height: 70px;
        padding: 15px 20px;
        gap: 15px;
    }

    .logo {
        font-size: 21px;
        
    }

    .logo-icon {
        width: 29px;
        height: 29px;
    }

    .logo-icon svg {
        width: 25px;
        height: 25px;
    }

    nav {
        display: none;
    }

    .nav-buttons {
        gap: 10px;
    }

    .nav-buttons a {
        font-size: 12px;
    }

    .signup {
        padding: 9px 15px;
    }

    .right-side {
        padding: 25px 15px;
    }

    .form-container {
        padding: 27px 20px;
        border-radius: 9px;
    }

    .form-container h2 {
        font-size: 30px;
    }

    .two-columns {
        grid-template-columns: 1fr;
        gap: 0;
    }

    .input-box {
        height: 55px;
    }

    .conditions {
        font-size: 11px;
    }

    .btn-register {
        height: 50px;
    }

}


@media (max-width: 400px) {

    .navbar {
        padding: 12px 15px;
    }

    .logo {
        font-size: 19px;
    }

    .btnconnecter {
        display: none;
    }

    .form-container {
        padding: 25px 17px;
    }

    .form-container h2 {
        font-size: 27px;
    }

}
        </style>
</head>

<body>

<header class="navbar">

<a href="index.php" class="logo">
<div class="logo-icon">
        <i data-lucide="calendar-days"></i>
    </div>
        Evently
    </a>



    </div>
    <div class="nav-buttons">

        <a
            href="Cox.php"
            class="btnconnecter"
        >
            Se connecter
        </a>

        <a
            href="inscription.php"
            class="signup"
        >
            S'inscrire
        </a>

    </div>

</header>


<main class="register-page">


    <section class="left-side">

        <div class="overlay"></div>

        <div class="left-content">

            <div class="brand">

                <i data-lucide="calendar-days"></i>

                <span>Evently</span>

            </div>


            <p class="subtitle">
                ORGANISEZ VOS ÉVÉNEMENTS EN TOUTE SIMPLICITÉ
            </p>


            <h1>
                Rejoignez notre<br>
                communauté !
            </h1>


            <p class="description">
                Créez votre compte pour accéder à tous nos services
                et commencer à organiser vos événements.
            </p>


            <div class="feature">

                <div class="feature-icon">

                    <i data-lucide="calendar-days"></i>

                </div>

                <div>

                    <h3>
                        Réservez facilement
                    </h3>

                    <p>
                        Trouvez la date parfaite pour votre événement
                    </p>

                </div>

            </div>


            <div class="feature">

                <div class="feature-icon">

                    <i data-lucide="shield-check"></i>

                </div>

                <div>

                    <h3>
                        Gérez vos réservations
                    </h3>

                    <p>
                        Suivez vos demandes et recevez des notifications
                    </p>

                </div>

            </div>


            <div class="feature">

                <div class="feature-icon">

                    <i data-lucide="users"></i>

                </div>

                <div>

                    <h3>
                        Une communauté active
                    </h3>

                    <p>
                        Découvrez des événements et des organisateurs
                    </p>

                </div>

            </div>


            <div class="slogan">
                Ensemble, créons<br>
                des moments uniques
            </div>

        </div>

    </section>


    <section class="right-side">

        <div class="form-container">

            <p class="form-small-title">
                CRÉER UN COMPTE
            </p>


            <h2>
                Inscription
            </h2>


            <p class="form-description">
                Remplissez les informations ci-dessous pour créer votre compte.
            </p>


            <?php if ($message): ?>

                <div class="message">
                    <?= htmlspecialchars($message) ?>
                </div>

            <?php endif; ?>


            <form method="POST">


                <div class="two-columns">


                    <div class="input-box">

                        <i data-lucide="user"></i>

                        <input
                            type="text"
                            name="nom"
                            placeholder="Nom"
                            required
                        >

                    </div>


                    <div class="input-box">

                        <i data-lucide="user"></i>

                        <input
                            type="text"
                            name="prenom"
                            placeholder="Prénom"
                            required
                        >

                    </div>

                </div>


                <div class="input-box">

                    <i data-lucide="mail"></i>

                    <input
                        type="email"
                        name="email"
                        placeholder="Email"
                        autocomplete="off"
                        required
                    >

                </div>


                <div class="input-box">

                    <i data-lucide="phone"></i>

                    <input
                        type="tel"
                        name="telephone"
                        placeholder="Téléphone"
                    >

                </div>


                <div class="input-box password-box">

                    <i data-lucide="lock"></i>

                    <input
                        type="password"
                        name="mot_de_passe"
                        placeholder="Mot de passe"
                        autocomplete="new-password"
                        required
                    >

                </div>


                <label class="conditions">

                    <input
                        type="checkbox"
                        name="conditions"
                        required
                    >

                    <span>
                        J'accepte les
                        <a href="#">
                            conditions d'utilisation
                        </a>
                        et la
                        <a href="#">
                            politique de confidentialité
                        </a>.
                    </span>

                </label>


<button type="submit" name="inscrire" class="btn-register">
    Créer mon compte
</button>


                <div class="already-account">

                    <span>
                        Vous avez déjà un compte ?
                    </span>

                    <a href="Cox.php">
                        Se connecter
                    </a>

                </div>


            </form>

        </div>

    </section>

</main>


<script>

    lucide.createIcons();

</script>

</body>

</html>