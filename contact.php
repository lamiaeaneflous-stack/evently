<?php
session_start();
$success = "";
$errors = [];
$nom = "";
$email = "";
$sujet = "";
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $sujet = trim($_POST["sujet"] ?? "");
    $message = trim($_POST["message"] ?? "");
    if (
        $nom === "" ||
        $email === "" ||
        $sujet === "" ||
        $message === ""
    ) {
        $errors[] = "Veuillez remplir tous les champs.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Adresse email invalide.";
    }
    if (empty($errors)) {
        $success = "Votre message a été envoyé avec succès.";
        $nom = "";
        $email = "";
        $sujet = "";
        $message = "";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Evently</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f8fafc;
            color: #1f2937;
        }

        .navbar {
            background: #064e3b;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .logo {
            color: white;
            text-decoration: none;
            font-size: 27px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }



        .nav-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .nav-btn {
            text-decoration: none;
            color: white;
            border: 1px solid #a7f3d0;
            padding: 9px 14px;
            border-radius: 7px;
            transition: 0.3s;
        }

        .nav-btn:hover {
            background: white;
            color: #064e3b;
        }
        .section {
            padding: 45px 20px;
        }

        .section-title {
            text-align: center;
            color: #064e3b;
            font-size: 32px;
            margin-bottom: 35px;
        }

        .contact-container {
            max-width: 1100px;
            margin: 40px auto;
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 30px;
        }

        .contact-info {
            background: #e1eee7;
            padding: 32px;
            border-radius: 15px;
            border-left: 5px solid #065f46;
        }

        .contact-info h2 {
            color: #064e3b;
            margin-bottom: 20px;
            font-size: 25px;
        }

        .contact-info p {
            margin: 18px 0;
            line-height: 1.7;
            color: #374151;
        }

        .contact-info strong {
            color: #064e3b;
        }

        .contact-form {
            background: white;
            padding: 32px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .contact-form h2 {
            color: #064e3b;
            margin-bottom: 22px;
            font-size: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #374151;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 13px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            font-size: 15px;
            transition: 0.3s;
        }

        .contact-form input:focus,
        .contact-form textarea:focus {
            border-color: #065f46;
            box-shadow: 0 0 0 2px #d1fae5;
        }

        .contact-form textarea {
            min-height: 145px;
            resize: vertical;
        }

        .btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #065f46;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #064e3b;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 13px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 13px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .error p {
            margin: 5px 0;
        }

        .footer {
            background: #064e3b;
            color: white;
            margin-top: 30px;
        }

        .footer-container {
            max-width: 1100px;
            margin: auto;
            padding: 35px 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .footer h3 {
            margin-bottom: 15px;
            color: #a7f3d0;
        }

        .footer p {
            line-height: 1.8;
            color: #e5e7eb;
        }

        .footer a {
            display: block;
            color: #e5e7eb;
            text-decoration: none;
            margin: 8px 0;
            transition: 0.3s;
        }

        .footer a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid #065f46;
            text-align: center;
            padding: 18px;
            color: #d1fae5;
        }

        @media (max-width: 768px) {

            .navbar {
                justify-content: center;
            }

            .nav-links {
                gap: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }

            .contact-container {
                grid-template-columns: 1fr;
                margin: 20px auto;
            }

            .footer-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .section-title {
                font-size: 27px;
            }

        }

    </style>

</head>

<body>
    <header class="navbar">
        <a href="index.php" class="logo">
            Evently
        </a>
        <div class="nav-buttons">
            <a href="index.php" class="nav-btn">
                Accueil
            </a>
            <a href="profile.php" class="nav-btn">
                profile
            </a>
    </div>
    </header>
    <section class="section">
        <h1 class="section-title">
            Contactez-nous
        </h1>
        <div class="contact-container">
            <div class="contact-info">
                <h2>
                    Evently
                </h2>
                <p>
                    Bienvenue sur Evently, votre plateforme de réservation
                    et d'organisation d'événements.
                </p>
                <p>
                    <strong>Email :</strong>
                    contact@gmail.com
                </p>
                <p>
                    <strong>Téléphone :</strong>
                    06 00 00 00 00
                </p>
                <p>
                    <strong>Adresse :</strong>
                    Casablanca, Maroc
                </p>
                <p>
                    Notre équipe est à votre disposition pour répondre
                    à toutes vos questions et demandes.
                </p>
            </div>
            <div class="contact-form">
                <h2>
                    Envoyez-nous un message
                </h2>
                <?php if (!empty($success)): ?>
                    <p class="success">
                        <?= htmlspecialchars($success) ?>
                    </p>
                <?php endif; ?>
                <?php if (!empty($errors)): ?>
                    <div class="error">
                        <?php foreach ($errors as $error): ?>
                            <p>
                                <?= htmlspecialchars($error) ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="contact.php">
                    <div class="form-group">
                        <label for="nom">
                            Nom complet
                        </label>
                        <input type="text"id="nom"name="nom"value="<?= htmlspecialchars($nom) ?>"placeholder="Entrez votre nom"required>
                    </div>
                    <div class="form-group">
                        <label for="email">
                            Email
                        </label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="exemple@gmail.com" required>
                    </div>
                    <div class="form-group">
                        <label for="sujet">
                            Sujet
                        </label>
                        <input type="text" id="sujet" name="sujet"value="<?= htmlspecialchars($sujet) ?>"placeholder="Sujet de votre message"required>
                    </div>
                    <div class="form-group">
                        <label for="message">
                            Message
                        </label>
                        <textarea id="message" name="message" placeholder="Écrivez votre message..." required><?= htmlspecialchars($message) ?></textarea>
                    </div>
                    <button type="submit" class="btn">
                        Envoyer le message
                    </button>
                </form>
            </div>
        </div>
    </section>
    <footer class="footer">
        <div class="footer-container">
            <div>
                <h3>
                    Evently
                </h3>
                <p>
                    Votre plateforme pour découvrir et réserver
                    des événements facilement.
                </p>
            </div>
            <div>
                <h3>
                    Navigation
                </h3>
                <a href="index.php">
                    Accueil
                </a>
                <a href="contact.php">
                    Contact
                </a>
            </div>
            <div>
                <h3>
                    Contact
                </h3>
                <p>
                    Email : contact@gmail.com
                </p>
                <p>
                    Téléphone : 06 00 00 00 00
                </p>
                <p>
                    Casablanca, Maroc
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>
                © 2026 Evently. Tous droits réservés.
            </p>
        </div>
    </footer>
</body>
</html>