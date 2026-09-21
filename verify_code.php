<?php
session_start();
include('connexion.php');

$error = "";

if (!isset($_SESSION['reset_contact'])) {
    header("Location: forgot_password.php");
    exit();
}

$contact = $_SESSION['reset_contact'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $code = trim($_POST['code']);

    if (!empty($code)) {

        $stmt = $pdo->prepare("
            SELECT * FROM utilisateurs
            WHERE email = :contact OR telephone = :contact
        ");

        $stmt->execute([
            'contact' => $contact
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            if (
                $user['reset_code'] == $code &&
                !empty($user['reset_expiration']) &&
                strtotime($user['reset_expiration']) > time()
            ) {

                $_SESSION['reset_user_id'] =
                    $user['id_utilisateur'];

                header("Location: reset_password.php");
                exit();

            } else {

                $error = "Code incorrect ou expiré.";
            }

        } else {

            $error = "Utilisateur introuvable.";
        }

    } else {

        $error = "Veuillez entrer le code.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Vérification</title>
    <link rel="stylesheet" href="ccc.css">
</head>

<body>

<header class="navbar">

    <a href="index.php" class="logo">
        Evently
    </a>

</header>


<div class="page-center">

    <div class="login-card">

        <div class="card-line"></div>

        <h2>Vérification</h2>

        <p class="subtitle">
            Entrez le code de récupération reçu.
        </p>


        <?php if (!empty($error)): ?>

            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>

        <?php endif; ?>


        <?php if (isset($_SESSION['reset_code_demo'])): ?>

            <div class="demo-code">
                Code de test :
                <strong>
                    <?= $_SESSION['reset_code_demo'] ?>
                </strong>
            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="form-group">

                <label for="code">
                    Code de récupération
                </label>

                <input
                    type="text"
                    name="code"
                    id="code"
                    maxlength="6"
                    placeholder="Ex : 583921"
                    required
                >

            </div>


            <button type="submit" class="submit">
                Vérifier le code
            </button>

        </form>


        <p class="back">
            <a href="forgot_password.php">
                ← Retour
            </a>
        </p>

    </div>

</div>

</body>
</html>