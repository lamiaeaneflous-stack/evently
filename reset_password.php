<?php
session_start();
include('connexion.php');

$error = "";
$success = "";

if (!isset($_SESSION['reset_user_id'])) {

    header("Location: forgot_password.php");
    exit();
}

$user_id = $_SESSION['reset_user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    if (empty($password) || empty($confirm_password)) {

        $error = "Veuillez remplir tous les champs.";

    } elseif ($password !== $confirm_password) {

        $error = "Les mots de passe ne correspondent pas.";

    } else {

        $stmt = $pdo->prepare("
            UPDATE utilisateurs
            SET mot_de_passe = :password,
                reset_code = NULL,
                reset_expiration = NULL
            WHERE id_utilisateur = :id
        ");

        $stmt->execute([
            'password' => $password,
            'id' => $user_id
        ]);


        // Supprimer les sessions de récupération
        unset($_SESSION['reset_user_id']);
        unset($_SESSION['reset_contact']);
        unset($_SESSION['reset_code_demo']);


        $success =
            "Votre mot de passe a été modifié avec succès.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Nouveau mot de passe</title>
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

        <h2>Nouveau mot de passe</h2>

        <p class="subtitle">
            Entrez votre nouveau mot de passe.
        </p>


        <?php if (!empty($error)): ?>

            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>

        <?php endif; ?>


        <?php if (!empty($success)): ?>

            <p class="success">
                <?= htmlspecialchars($success) ?>
            </p>

            <a href="Cox.php" class="submit-link">
                Retour à la connexion
            </a>

        <?php else: ?>


            <form method="POST">

                <div class="form-group">

                    <label for="password">
                        Nouveau mot de passe
                    </label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Nouveau mot de passe"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="confirm_password">
                        Confirmer le mot de passe
                    </label>

                    <input
                        type="password"
                        name="confirm_password"
                        id="confirm_password"
                        placeholder="Confirmer le mot de passe"
                        required
                    >

                </div>


                <button type="submit" class="submit">
                    Modifier le mot de passe
                </button>

            </form>

        <?php endif; ?>

    </div>

</div>

</body>
</html>