<?php
session_start();
include('connexion.php');

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $contact = trim($_POST['contact']);

    if (!empty($contact)) {

        $stmt = $pdo->prepare("
            SELECT * FROM utilisateurs
            WHERE email = :contact OR telephone = :contact
        ");

        $stmt->execute([
            'contact' => $contact
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            // Générer un code de 6 chiffres
            $code = random_int(100000, 999999);

            // Code valable pendant 10 minutes
            $expiration = date(
                'Y-m-d H:i:s',
                time() + 600
            );

            $update = $pdo->prepare("
                UPDATE utilisateurs
                SET reset_code = :code,
                    reset_expiration = :expiration
                WHERE id_utilisateur = :id
            ");

            $update->execute([
                'code' => $code,
                'expiration' => $expiration,
                'id' => $user['id_utilisateur']
            ]);

            // Stocker l'utilisateur dans la session
            $_SESSION['reset_contact'] = $contact;

            // Pour un projet scolaire :
            // on affiche temporairement le code
            $_SESSION['reset_code_demo'] = $code;

            header("Location: verify_code.php");
            exit();

        } else {

            $error = "Aucun compte trouvé avec cet email ou numéro.";
        }

    } else {

        $error = "Veuillez entrer votre email ou numéro de téléphone.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="ccc.css">
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


<div class="page-center">

    <div class="login-card">

        <div class="card-line"></div>

        <h2>Mot de passe oublié ?</h2>

        <p class="subtitle">
            Entrez votre email ou numéro de téléphone
            pour récupérer votre compte.
        </p>


        <?php if (!empty($error)): ?>

            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>

        <?php endif; ?>


        <form method="POST">

            <div class="form-group">

                <label for="contact">
                    Email ou numéro de téléphone
                </label>

                <input
                    type="text"
                    name="contact"
                    id="contact"
                    placeholder="exemple@gmail.com"
                    required
                >

            </div>


            <button type="submit" class="submit">
                Continuer
            </button>

        </form>


        <p class="back">
            <a href="Cox.php">
                ← Retour à la connexion
            </a>
        </p>

    </div>

</div>

</body>
</html>