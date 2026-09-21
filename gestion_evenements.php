<?php

session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

include("connexion.php");

if (isset($_POST['ajouter'])) {

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
                INSERT INTO evenements
                (nom, type, date_evenement, heure_debut, heure_fin, lieu, image, statut)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'accepte')
            ");

            $stmt->execute([
                $nom,
                $type,
                $date_evenement,
                $heure_debut,
                $heure_fin,
                $lieu,
                $image
            ]);

            header("Location: gestion_evenements.php");
            exit;
        }
    }
}

if (isset($_GET['supprimer'])) {

    $id = (int) $_GET['supprimer'];

    $stmt = $pdo->prepare("
        DELETE FROM evenements
        WHERE id_evenement = ?
    ");

    $stmt->execute([$id]);

    header("Location: gestion_evenements.php");
    exit;
}

$stmt = $pdo->query("
    SELECT *
    FROM evenements
    ORDER BY id_evenement DESC
");

$evenements = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestion des événements</title>

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
    max-width: 1250px;
    margin: auto;
}

h1 {
    text-align: center;
    font-size: 30px;
    color: #285e54;
    margin-bottom: 30px;
}

.form-container,
.table-container {
    background: white;
    padding: 30px;
    border-radius: 16px;
    margin-bottom: 30px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}

.form-container h2,
.table-container h2 {
    color: #285e54;
    font-size: 21px;
    margin-bottom: 25px;
}

.form-group {
    margin-bottom: 18px;
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

.btn-retour {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: #285e54;
    color: white;
    text-decoration: none;
    padding: 11px 18px;
    border-radius: 8px;
    font-weight: bold;
    font-size: 14px;
    transition: 0.3s;
    width: fit-content;
    margin-left: auto;
    margin-bottom: 25px;
}

.btn-retour:hover {
    background: #1f4d44;
    transform: translateY(-2px);
}

button {
    background: #285e54;
    color: white;
    border: none;
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

.error {
    padding: 13px 16px;
    margin-bottom: 20px;
    border-radius: 8px;
    background: #ffe5e5;
    color: #d9534f;
    font-weight: bold;
    border-left: 4px solid #d9534f;
}

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    min-width: 1100px;
    border-collapse: collapse;
    overflow: hidden;
}

thead th {
    padding: 15px 13px;
    background: #285e54;
    color: white;
    text-align: center;
    font-size: 13px;
    white-space: nowrap;
}

tbody td {
    padding: 14px 12px;
    border-bottom: 1px solid #eeeeee;
    text-align: center;
    color: #333;
    font-size: 13px;
    vertical-align: middle;
}

tbody tr {
    transition: 0.2s;
}

tbody tr:hover {
    background: #f8faf9;
}

tbody tr:last-child td {
    border-bottom: none;
}

.event-image {
    width: 75px;
    height: 55px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #eeeeee;
}

.no-image {
    color: #999;
    font-size: 12px;
}

.actions {
    white-space: nowrap;
}

.actions a {
    text-decoration: none;
    color: white;
    padding: 8px 12px;
    border-radius: 7px;
    margin: 2px;
    display: inline-block;
    font-size: 12px;
    font-weight: bold;
    transition: 0.3s;
}

.edit {
    background: #285e54;
}

.edit:hover {
    background: #1f4d44;
    transform: translateY(-1px);
}

.delete {
    background: #d99a2b;
}

.delete:hover {
    background: #c58920;
    transform: translateY(-1px);
}

td:nth-child(2) {
    font-weight: bold;
    color: #285e54;
}

td:nth-child(5),
td:nth-child(6) {
    font-weight: bold;
    white-space: nowrap;
}

td:nth-child(8) {
    font-weight: bold;
    color: #285e54;
}

@media (max-width: 768px) {

    body {
        padding: 20px 10px;
    }

    .form-container,
    .table-container {
        padding: 20px 15px;
        border-radius: 12px;
    }

    h1 {
        font-size: 24px;
    }

    .heure-container {
        grid-template-columns: 1fr;
        gap: 0;
    }

    table {
        min-width: 1100px;
    }

    .btn-retour {
        width: fit-content;
        margin-left: auto;
        justify-content: center;
    }
}

@media (max-width: 500px) {

    h1 {
        font-size: 21px;
    }

    .form-container h2,
    .table-container h2 {
        font-size: 18px;
    }

    button {
        width: 100%;
    }

}

    </style>

</head>

<body>

<div class="container">

    <a href="admin.php" class="btn-retour">
        ← Retour à l'admin
    </a>

    <h1>Gestion des événements</h1>

    <?php if (isset($erreur)): ?>

        <div class="error">
            <?= htmlspecialchars($erreur) ?>
        </div>

    <?php endif; ?>

    <div class="form-container">

        <h2>Ajouter un événement</h2>

        <form method="POST">

            <div class="form-group">

                <label>Nom de l'événement</label>

                <input
                    type="text"
                    name="nom"
                    required
                >

            </div>

            <div class="form-group">

                <label>Type</label>

                <select name="type" required>

                    <option value="">Sélectionner le type</option>
                    <option value="Conference">Conference</option>
                    <option value="Soiree gala">Soiree gala</option>
                    <option value="Wedding">Wedding</option>
                    <option value="Birthday">Birthday</option>
                    <option value="Concert">Concert</option>
                    <option value="Workshop">Workshop</option>
                    <option value="Seminar">Seminar</option>
                    <option value="Festival">Festival</option>
                    <option value="Party">Party</option>

                </select>

            </div>

            <div class="form-group">

                <label>Date</label>

                <input
                    type="date"
                    name="date_evenement"
                    required
                >

            </div>

            <div class="heure-container">

                <div class="form-group">

                    <label>Heure de début</label>

                    <input
                        type="time"
                        name="heure_debut"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Heure de fin</label>

                    <input
                        type="time"
                        name="heure_fin"
                        required
                    >

                </div>

            </div>

            <div class="form-group">

                <label>Lieu</label>

                <input
                    type="text"
                    name="lieu"
                    required
                >

            </div>

            <div class="form-group">

                <label>Image</label>

                <input
                    type="text"
                    name="image"
                    placeholder="exemple.jpg"
                    required
                >

            </div>

            <button
                type="submit"
                name="ajouter"
            >
                Ajouter
            </button>

        </form>

    </div>

    <div class="table-container">

        <h2>Liste des événements</h2>

        <br>

        <table>

            <thead>

                <tr>
                    <th>Image</th>
                    <th>Événement</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Lieu</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

            <?php if (count($evenements) > 0): ?>

                <?php foreach ($evenements as $evenement): ?>

                    <tr>

                        <td>

                            <?php if (!empty($evenement['image'])): ?>

                                <img
                                    src="<?= htmlspecialchars($evenement['image']) ?>"
                                    alt="Image événement"
                                    class="event-image"
                                >

                            <?php else: ?>

                                <span class="no-image">
                                    Aucune image
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>
                            <?= htmlspecialchars($evenement['nom']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($evenement['type']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($evenement['date_evenement']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($evenement['heure_debut']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($evenement['heure_fin']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($evenement['lieu']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($evenement['statut']) ?>
                        </td>

                        <td class="actions">

                            <a
                                href="modifier.php?id=<?= (int) $evenement['id_evenement'] ?>"
                                class="edit"
                            >
                                Modifier
                            </a>

                            <a
                                href="?supprimer=<?= (int) $evenement['id_evenement'] ?>"
                                class="delete"
                                onclick="return confirm('Voulez-vous vraiment supprimer cet événement ?');"
                            >
                                Supprimer
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>

                    <td colspan="9">
                        Aucun événement trouvé.
                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>