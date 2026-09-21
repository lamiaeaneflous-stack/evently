<?php
session_start();
include("connexion.php");

if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: admin.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $pdo->prepare("
            SELECT *
            FROM utilisateurs
            WHERE email = ?
            LIMIT 1
        ");

        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password === $user['mot_de_passe']) {
            $_SESSION['admin'] = true;
            $_SESSION['role'] = $user['role'];
            $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];

            header("Location: admin.php");
            exit;
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Evently</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f7f7;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
            color: #333;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
        }

        .login-box {
            background: white;
            padding: 38px;
            border-radius: 14px;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.08);
            border-top: 4px solid #285e54;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #285e54;
            font-size: 30px;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .header-line {
            width: 40px;
            height: 2px;
            background: #285e54;
            margin: 10px auto;
        }

        .header p {
            color: #888;
            font-size: 13px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #285e54;
            font-size: 13px;
            font-weight: bold;
        }

        input {
            width: 100%;
            height: 46px;
            padding: 0 14px;
            border: 1px solid #d9dfdd;
            border-radius: 7px;
            background: #fafcfc;
            color: #333;
            font-size: 14px;
            transition: 0.3s;
            margin-bottom: 20px;
        }

        input:focus {
            outline: none;
            border-color: #285e54;
            background: white;
            box-shadow: 0 0 0 3px rgba(40, 94, 84, 0.08);
        }

        input::placeholder {
            color: #aaa;
        }

        .error {
            background: #fff0f0;
            color: #d9534f;
            border-left: 3px solid #d9534f;
            padding: 11px 13px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .btn-login {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 7px;
            background: #285e54;
            color: white;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 5px;
        }

        .btn-login:hover {
            background: #1f4d44;
            transform: translateY(-1px);
        }

        .bottom {
            text-align: center;
            margin-top: 22px;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #285e54;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-home span {
            color: #285e54;
            font-size: 16px;
        }

        .btn-home:hover {
            color: #1f4d44;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 18px;
            border-top: 1px solid #eeeeee;
            color: #aaa;
            font-size: 11px;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }

            .login-box {
                padding: 30px 22px;
            }

            .header h1 {
                font-size: 27px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <div class="header">
                <h1>Evently</h1>
                <div class="header-line"></div>
                <p>Administration des événements</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <label for="email">Votre email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Entrez votre email"
                    autocomplete="off"
                    required
                >

                <label for="password">Mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Entrez votre mot de passe"
                    autocomplete="new-password"
                    required
                >

                <button type="submit" name="login" class="btn-login">
                    Se connecter
                </button>
            </form>

            <div class="bottom">
                <a href="index.php" class="btn-home">
                    <span>←</span>
                    Retour à l'accueil
                </a>
            </div>

            <div class="footer">
                Evently © 2026
            </div>
        </div>
    </div>
</body>
</html>