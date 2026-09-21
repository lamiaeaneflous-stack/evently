```php
<?php

include "connexion.php";


/* =========================================================
   VERIFIER L'ID DE L'EVENEMENT
   ========================================================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Événement introuvable.");
}

$id_evenement = (int) $_GET['id'];


/* =========================================================
   RECUPERER L'EVENEMENT
   ========================================================= */

$sql = $pdo->prepare("
    SELECT
        id_evenement,
        nom,
        type,
        date_evenement,
        heure_debut,
        heure_fin,
        lieu,
        statut,
        image
    FROM evenements
    WHERE id_evenement = ?
");

$sql->execute([$id_evenement]);

$event = $sql->fetch(PDO::FETCH_ASSOC);


/* =========================================================
   VERIFIER SI L'EVENEMENT EXISTE
   ========================================================= */

if (!$event) {
    die("Événement introuvable.");
}

?>

<!DOCTYPE html>

<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($event['nom']) ?>
    </title>

    <link rel="stylesheet"
          href="bbb.css">

</head>


<body>


<div class="container">


    <!-- =====================================================
         HEADER
         ===================================================== -->

    <div class="rheader">

        <h2>
            Détails de l'événement
        </h2>

    </div>



    <!-- =====================================================
         IMAGE
         ===================================================== -->

    <?php if (!empty($event['image'])): ?>

        <img
            src="<?= htmlspecialchars($event['image']) ?>"
            alt="Image de l'événement"
            width="400"
        >

    <?php endif; ?>



    <!-- =====================================================
         NOM
         ===================================================== -->

    <h3>

        <?= htmlspecialchars($event['nom']) ?>

    </h3>



    <!-- =====================================================
         TYPE
         ===================================================== -->

    <p>

        <strong>Type :</strong>

        <?= htmlspecialchars($event['type']) ?>

    </p>



    <!-- =====================================================
         DATE
         ===================================================== -->

    <p>

        <strong>Date :</strong>

        <?= htmlspecialchars($event['date_evenement']) ?>

    </p>



    <!-- =====================================================
         HEURE
         ===================================================== -->

    <p>

        <strong>Heure :</strong>

        <?= htmlspecialchars($event['heure_debut']) ?>

        -

        <?= htmlspecialchars($event['heure_fin']) ?>

    </p>



    <!-- =====================================================
         LIEU
         ===================================================== -->

    <p>

        <strong>Lieu :</strong>

        <?= htmlspecialchars($event['lieu']) ?>

    </p>



    <!-- =====================================================
         STATUT
         ===================================================== -->

    <p>

        <strong>Statut :</strong>

        <?= htmlspecialchars($event['statut']) ?>

    </p>



    <!-- =====================================================
         BOUTON RESERVATION
         ===================================================== -->

    <?php if ($event['statut'] === 'accepte'): ?>

        <a
            href="reservation.php?id=<?= (int) $event['id_evenement'] ?>"
            class="btn-submit"
        >
            Réserver maintenant
        </a>

    <?php else: ?>

        <p>
            Cet événement n'est pas disponible pour le moment.
        </p>

    <?php endif; ?>


</div>


</body>

</html>
```
