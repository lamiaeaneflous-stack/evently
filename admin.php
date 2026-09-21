<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

include("connexion.php");

if (isset($_GET['accepter'])) {

    $id = (int) $_GET['accepter'];

    $stmt = $pdo->prepare("
        UPDATE evenements
        SET statut = 'accepte'
        WHERE id_evenement = ?
    ");

    $stmt->execute([$id]);

    header("Location: admin.php");
    exit;
}

if (isset($_GET['non_disponible'])) {

    $id = (int) $_GET['non_disponible'];

    $stmt = $pdo->prepare("
        SELECT
            e.nom,
            e.date_evenement,
            e.heure_debut,
            e.heure_fin,
            u.nom AS nom_organisateur,
            u.prenom AS prenom_organisateur,
            u.email
        FROM evenements e
        INNER JOIN utilisateurs u
            ON e.id_organisateur = u.id_utilisateur
        WHERE e.id_evenement = ?
    ");

    $stmt->execute([$id]);

    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($event) {

        $update = $pdo->prepare("
            UPDATE evenements
            SET statut = 'refuse'
            WHERE id_evenement = ?
        ");

        $update->execute([$id]);

        $to = $event['email'];

        $subject = "Événement non disponible - Evently";

        $message = "Bonjour "
            . $event['prenom_organisateur']
            . " "
            . $event['nom_organisateur']
            . ",\n\n";

        $message .= "Nous vous informons que votre demande d'événement « "
            . $event['nom']
            . " » prévue le "
            . $event['date_evenement']
            . " de "
            . $event['heure_debut']
            . " à "
            . $event['heure_fin']
            . " n'est malheureusement pas disponible.\n\n";

        $message .= "La date ou l'heure demandée est déjà occupée par un autre événement.\n\n";

        $message .= "Nous vous invitons à modifier la date ou l'heure de votre événement, puis à soumettre une nouvelle demande.\n\n";

        $message .= "Merci pour votre compréhension.\n\n";

        $message .= "Cordialement,\n";
        $message .= "L'équipe Evently";

        $headers = "From: noreply@evently.com\r\n";
        $headers .= "Reply-To: noreply@evently.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        mail($to, $subject, $message, $headers);
    }

    header("Location: admin.php");
    exit;
}

if (isset($_GET['delete'])) {

    $id = (int) $_GET['delete'];

    $stmt = $pdo->prepare("
        DELETE FROM evenements
        WHERE id_evenement = ?
    ");

    $stmt->execute([$id]);

    header("Location: admin.php");
    exit;
}

$events = $pdo->query("
    SELECT *
    FROM evenements
    ORDER BY id_evenement DESC
");

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Evently</title>

    <style>
        <style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 40px 20px;
    font-family: Arial, sans-serif;
    background: #f5f7f7;
    color: #333;
}

.container {
    width: 100%;
    max-width: 1250px;
    margin: auto;
    background: white;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
}
.top-buttons {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    margin-bottom: 25px;
}
.gestion-btn {
    margin: 0;
}

.gestion-btn a,
.logout {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    background: #285e54;
    color: white;
    padding: 11px 18px;
    border-radius: 8px;
    font-weight: bold;
    font-size: 14px;
    transition: 0.3s;
}

.gestion-btn a:hover,
.logout:hover {
    background: #1f4d44;
    transform: translateY(-2px);
}
h1 {
    text-align: center;
    font-size: 30px;
    color: #285e54;
    margin: 10px 0 8px;
}

h2 {
    text-align: center;
    font-size: 20px;
    font-weight: normal;
    color: #555;
    margin: 0 0 30px;
}

.table-container {
    width: 100%;
    overflow-x: auto;
    border-radius: 12px;
    border: 1px solid #e5e5e5;
}

table {
    width: 100%;
    min-width: 1050px;
    border-collapse: collapse;
    background: white;
}

thead th {
    padding: 16px 14px;
    background: #285e54;
    color: white;
    text-align: left;
    font-size: 14px;
    font-weight: bold;
    white-space: nowrap;
}

tbody td {
    padding: 15px 14px;
    border-bottom: 1px solid #eeeeee;
    color: #333;
    font-size: 14px;
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

.action {
    text-decoration: none;
    color: white;
    padding: 9px 13px;
    border-radius: 7px;
    display: inline-block;
    margin: 2px;
    font-size: 12px;
    font-weight: bold;
    border: none;
    cursor: pointer;
    transition: 0.3s;
    white-space: nowrap;
}

.accept {
    background: #285e54;
}

.accept:hover {
    background: #1f4d44;
    transform: translateY(-1px);
}

.unavailable {
    background: #d99a2b;
}

.unavailable:hover {
    background: #c58920;
    transform: translateY(-1px);
}

.attente {
    color: #d99a2b;
    font-weight: bold;
    background: #fff4d6;
    padding: 7px 11px;
    border-radius: 20px;
    display: inline-block;
    font-size: 12px;
}

.accepte {
    color: #285e54;
    font-weight: bold;
    background: #e5f3ef;
    padding: 7px 11px;
    border-radius: 20px;
    display: inline-block;
    font-size: 12px;
}

.refuse-status {
    color: #d9534f;
    font-weight: bold;
    background: #ffe5e5;
    padding: 7px 11px;
    border-radius: 20px;
    display: inline-block;
    font-size: 12px;
}

td:nth-child(1) {
    font-weight: bold;
    color: #285e54;
}

td:nth-child(4),
td:nth-child(5) {
    font-weight: bold;
    white-space: nowrap;
}

td:nth-child(3) {
    white-space: nowrap;
}

@media (max-width: 900px) {

    body {
        padding: 20px 10px;
    }

    .container {
        padding: 22px 15px;
        border-radius: 14px;
    }

    .gestion-btn {
        text-align: center;
    }

    .top {
        justify-content: center;
    }

    h1 {
        font-size: 24px;
    }

    h2 {
        font-size: 18px;
        margin-bottom: 22px;
    }

    table {
        min-width: 1050px;
    }
}

@media (max-width: 500px) {

    body {
        padding: 10px;
    }

    .container {
        padding: 18px 10px;
    }

    .gestion-btn a,
    .logout {
        width: 100%;
        justify-content: center;
        text-align: center;
    }

    h1 {
        font-size: 21px;
    }

    h2 {
        font-size: 16px;
    }
}
   </style>
</head>

<body>

<div class="container">
<div class="top-buttons">

    <div class="gestion-btn">
        <a href="gestion_evenements.php">
             Gestion des événements
        </a>
    </div>

    <a href="logout.php" class="logout">
        Déconnexion
    </a>

</div>

    <h1>Dashboard Admin - Evently</h1>

    <h2>Validation des événements</h2>

    <div class="table-container">

        <table>

            <thead>
                <tr>
                    <th>Événement</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Heure début</th>
                    <th>Heure fin</th>
                    <th>Lieu</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php while ($event = $events->fetch(PDO::FETCH_ASSOC)): ?>

                <tr>

                    <td>
                        <?= htmlspecialchars($event['nom']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($event['type']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($event['date_evenement']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($event['heure_debut']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($event['heure_fin']) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($event['lieu']) ?>
                    </td>

                    <td>

                        <?php if ($event['statut'] === 'en_attente'): ?>

                            <span class="attente">
                                En attente
                            </span>

                        <?php elseif ($event['statut'] === 'accepte'): ?>

                            <span class="accepte">
                                Accepté
                            </span>

                        <?php elseif ($event['statut'] === 'refuse'): ?>

                            <span class="refuse-status">
                                Non disponible
                            </span>

                        <?php else: ?>

                            <?= htmlspecialchars($event['statut']) ?>

                        <?php endif; ?>

                    </td>

                    <td>

                        <?php if ($event['statut'] === 'en_attente'): ?>

                            <a
                                href="admin.php?accepter=<?= (int) $event['id_evenement'] ?>"
                                class="action accept"
                                onclick="return confirm('Voulez-vous accepter cet événement ?');"
                            >
                                Accepter
                            </a>

                            <a
                                href="admin.php?non_disponible=<?= (int) $event['id_evenement'] ?>"
                                class="action unavailable"
                                onclick="return confirm('La date ou l’heure n’est pas disponible. Voulez-vous envoyer un message au client ?');"
                            >
                                Non disponible
                            </a>

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

</body>

</html>