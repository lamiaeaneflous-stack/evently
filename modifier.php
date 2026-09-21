<?php

session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

include("connexion.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: gestion_evenements.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT *
    FROM evenements
    WHERE id_evenement = ?
");

$stmt->execute([$id]);

$evenement = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$evenement) {
    header("Location: gestion_evenements.php");
    exit;
}

if (isset($_POST['modifier'])) {

    $nom = trim($_POST['nom'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $date_evenement = $_POST['date_evenement'] ?? '';
    $heure_debut = $_POST['heure_debut'] ?? '';
    $heure_fin = $_POST['heure_fin'] ?? '';
    $lieu = trim($_POST['lieu'] ?? '');
    $image = trim($_POST['image'] ?? '');

    if (
        $nom !== '' &&
        $type !== '' &&
        $date_evenement !== '' &&
        $heure_debut !== '' &&
        $heure_fin !== '' &&
        $lieu !== '' &&
        $image !== ''
    ) {

        if ($heure_fin <= $heure_debut) {

            $erreur = "L'heure de fin doit être supérieure à l'heure de début.";

        } else {

            $stmt = $pdo->prepare("
                UPDATE evenements
                SET
                    nom = ?,
                    type = ?,
                    date_evenement = ?,
                    heure_debut = ?,
                    heure_fin = ?,
                    lieu = ?,
                    image = ?
                WHERE id_evenement = ?
            ");

            $stmt->execute([
                $nom,
                $type,
                $date_evenement,
                $heure_debut,
                $heure_fin,
                $lieu,
                $image,
                $id
            ]);

            header("Location: gestion_evenements.php");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Modifier un événement</title>

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
    padding: 40px 20px;
    color: #333;
}

.container {
    width: 100%;
    max-width: 800px;
    margin: auto;
}

h1 {
    text-align: center;
    color: #285e54;
    font-size: 30px;
    margin-bottom: 30px;
}

.form-container {
    background: white;
    padding: 35px;
    border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
    font-size: 14px;
}

input,
select {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #d8dddd;
    border-radius: 8px;
    font-size: 14px;
    background: #fafcfc;
    color: #333;
    outline: none;
    transition: 0.3s;
}

input:focus,
select:focus {
    border-color: #285e54;
    box-shadow: 0 0 0 3px rgba(40, 94, 84, 0.10);
    background: white;
}

.heure-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.current-image {
    margin-top: 15px;
    padding: 15px;
    background: #f5f7f7;
    border-radius: 10px;
    border: 1px solid #e2e6e6;
}

.current-image p {
    color: #285e54;
    font-size: 13px;
    font-weight: bold;
    margin-bottom: 10px;
}

.current-image img {
    width: 180px;
    height: 120px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid #ddd;
    display: block;
}

.buttons {
    margin-top: 30px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
}

button {
    border: none;
    background: #285e54;
    color: white;
    padding: 12px 22px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #1f4d44;
    transform: translateY(-2px);
}

.btn-retour {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: #285e54;
    color: white;
    text-decoration: none;
    padding: 12px 22px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: bold;
    transition: 0.3s;
}

.btn-retour:hover {
    background: #1f4d44;
    transform: translateY(-2px);
}

.btn-annuler {
    background: #d99a2b;
    color: white;
    text-decoration: none;
    padding: 12px 22px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: bold;
    transition: 0.3s;
}

.btn-annuler:hover {
    background: #c58920;
    transform: translateY(-2px);
}

.error {
    padding: 13px 16px;
    margin-bottom: 22px;
    border-radius: 8px;
    background: #ffe5e5;
    color: #d9534f;
    font-weight: bold;
    border-left: 4px solid #d9534f;
}

@media (max-width: 768px) {

    body {
        padding: 20px 10px;
    }

    .form-container {
        padding: 25px 20px;
        border-radius: 12px;
    }

    h1 {
        font-size: 24px;
    }

    .heure-container {
        grid-template-columns: 1fr;
        gap: 0;
    }

    .buttons {
        flex-direction: column;
        align-items: stretch;
    }

    button,
    .btn-retour,
    .btn-annuler {
        width: 100%;
        text-align: center;
    }

    .current-image img {
        width: 100%;
        max-width: 180px;
    }
}

@media (max-width: 500px) {

    h1 {
        font-size: 21px;
    }

    .form-container {
        padding: 20px 15px;
    }

}

    </style>

</head>

<body>

<div class="container">

    <h1>Modifier l'événement</h1>

    <div class="form-container">

        <?php if (isset($erreur)): ?>

            <div class="error">
                <?= htmlspecialchars($erreur) ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <div class="form-group">

                <label>Nom de l'événement</label>

                <input
                    type="text"
                    name="nom"
                    value="<?= htmlspecialchars($evenement['nom']) ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Type</label>

                <select name="type" required>

                    <option value="">Sélectionner le type</option>

                    <option value="Conference"
                        <?= $evenement['type'] === 'Conference' ? 'selected' : '' ?>>
                        Conference
                    </option>

                    <option value="Soiree gala"
                        <?= $evenement['type'] === 'Soiree gala' ? 'selected' : '' ?>>
                        Soiree gala
                    </option>

                    <option value="Wedding"
                        <?= $evenement['type'] === 'Wedding' ? 'selected' : '' ?>>
                        Wedding
                    </option>

                    <option value="Birthday"
                        <?= $evenement['type'] === 'Birthday' ? 'selected' : '' ?>>
                        Birthday
                    </option>

                    <option value="Concert"
                        <?= $evenement['type'] === 'Concert' ? 'selected' : '' ?>>
                        Concert
                    </option>

                    <option value="Workshop"
                        <?= $evenement['type'] === 'Workshop' ? 'selected' : '' ?>>
                        Workshop
                    </option>

                    <option value="Seminar"
                        <?= $evenement['type'] === 'Seminar' ? 'selected' : '' ?>>
                        Seminar
                    </option>

                    <option value="Festival"
                        <?= $evenement['type'] === 'Festival' ? 'selected' : '' ?>>
                        Festival
                    </option>

                    <option value="Party"
                        <?= $evenement['type'] === 'Party' ? 'selected' : '' ?>>
                        Party
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Date</label>

                <input
                    type="date"
                    name="date_evenement"
                    value="<?= htmlspecialchars($evenement['date_evenement']) ?>"
                    required
                >

            </div>

            <div class="heure-container">

                <div class="form-group">

                    <label>Heure de début</label>

                    <input
                        type="time"
                        name="heure_debut"
                        value="<?= htmlspecialchars($evenement['heure_debut'] ?? '') ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Heure de fin</label>

                    <input
                        type="time"
                        name="heure_fin"
                        value="<?= htmlspecialchars($evenement['heure_fin'] ?? '') ?>"
                        required
                    >

                </div>

            </div>

            <div class="form-group">

                <label>Lieu</label>

                <input
                    type="text"
                    name="lieu"
                    value="<?= htmlspecialchars($evenement['lieu']) ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Image</label>

                <input
                    type="text"
                    name="image"
                    value="<?= htmlspecialchars($evenement['image']) ?>"
                    placeholder="exemple.jpg"
                    required
                >

                <?php if (!empty($evenement['image'])): ?>

                    <div class="current-image">

                        <p>Image actuelle</p>

                        <img
                            src="<?= htmlspecialchars($evenement['image']) ?>"
                            alt="Image événement"
                        >

                    </div>

                <?php endif; ?>

            </div>

            <div class="buttons">

                <button
                    type="submit"
                    name="modifier"
                >
                    Enregistrer les modifications
                </button>

                <a
                    href="gestion_evenements.php"
                    class="btn-retour"
                >
                    ← Retour aux événements
                </a>

                <a
                    href="gestion_evenements.php"
                    class="btn-annuler"
                >
                    Annuler
                </a>

            </div>

        </form>

    </div>

</div>

</body>

</html>