```php
<?php

session_start();

include "connexion.php";

$message = "";


/* =========================================================
   1. RECUPERER ID DE L'EVENEMENT
   ========================================================= */

if (isset($_GET['id'])) {

    $id_evenement = (int) $_GET['id'];

} elseif (isset($_POST['id_evenement'])) {

    $id_evenement = (int) $_POST['id_evenement'];

} else {

    die("Événement introuvable.");
}


/* =========================================================
   2. VERIFIER QUE L'EVENEMENT EXISTE
   ========================================================= */

$eventCheck = $pdo->prepare("
    SELECT
        id_evenement,
        nom,
        type,
        date_evenement,
        heure_debut,
        heure_fin,
        lieu,
        statut
    FROM evenements
    WHERE id_evenement = ?
");

$eventCheck->execute([$id_evenement]);

$event = $eventCheck->fetch(PDO::FETCH_ASSOC);


if (!$event) {
    die("Cet événement n'existe pas.");
}


/* =========================================================
   3. TRAITEMENT DE LA RESERVATION
   ========================================================= */

if (isset($_POST['submit'])) {

    /* -----------------------------------------------------
       Recuperer les informations du formulaire
       ----------------------------------------------------- */

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    $guests = (int) ($_POST['guests'] ?? 0);

    $date = $_POST['date'] ?? '';

    $heure_debut = $_POST['heure_debut'] ?? '';
    $heure_fin = $_POST['heure_fin'] ?? '';


    /* -----------------------------------------------------
       Verifier ID evenement
       ----------------------------------------------------- */

    $id_evenement = (int) ($_POST['id_evenement'] ?? 0);


    if ($id_evenement <= 0) {

        $message = "Événement invalide.";

    } else {


        /* -------------------------------------------------
           Verifier une nouvelle fois l'événement
           ------------------------------------------------- */

        $eventCheck = $pdo->prepare("
            SELECT
                id_evenement,
                nom,
                type,
                date_evenement,
                heure_debut,
                heure_fin,
                lieu,
                statut
            FROM evenements
            WHERE id_evenement = ?
        ");

        $eventCheck->execute([$id_evenement]);

        $event = $eventCheck->fetch(PDO::FETCH_ASSOC);


        if (!$event) {

            $message = "Cet événement n'existe pas.";

        } elseif ($heure_fin <= $heure_debut) {

            $message = "L'heure de fin doit être après l'heure de début.";

        } elseif ($guests <= 0) {

            $message = "Veuillez sélectionner le nombre de personnes.";

        } else {


            /* =============================================
               4. VERIFIER LE NOMBRE DE RESERVATIONS
               ============================================= */

            $countDay = $pdo->prepare("
                SELECT COUNT(*)
                FROM reservations
                WHERE date_reservation = ?
                AND statut IN ('en_attente', 'confirmee')
            ");

            $countDay->execute([$date]);

            $nombreEvenements = (int) $countDay->fetchColumn();


            if ($nombreEvenements >= 4) {

                $message = "Cette journée n'est pas disponible.";

            } else {


                /* =========================================
                   5. VERIFIER LE CHEVAUCHEMENT DES HORAIRES
                   ========================================= */

                $check = $pdo->prepare("
                    SELECT id_reservation
                    FROM reservations
                    WHERE date_reservation = ?
                    AND statut IN ('en_attente', 'confirmee')
                    AND heure_debut < ?
                    AND heure_fin > ?
                    LIMIT 1
                ");

                $check->execute([
                    $date,
                    $heure_fin,
                    $heure_debut
                ]);


                if ($check->fetch()) {

                    $message = "Cette plage horaire n'est pas disponible.";

                } else {


                    /* =====================================
                       6. RECHERCHER LE CLIENT
                       ===================================== */

                    $user = $pdo->prepare("
                        SELECT id_utilisateur
                        FROM utilisateurs
                        WHERE email = ?
                        LIMIT 1
                    ");

                    $user->execute([$email]);

                    $userData = $user->fetch(PDO::FETCH_ASSOC);


                    /* =====================================
                       7. CREER LE CLIENT S'IL N'EXISTE PAS
                       ===================================== */

                    if ($userData) {

                        $id_utilisateur = (int) $userData['id_utilisateur'];

                    } else {

                        $insertUser = $pdo->prepare("
                            INSERT INTO utilisateurs
                            (
                                nom,
                                email,
                                telephone,
                                mot_de_passe,
                                role
                            )
                            VALUES (?, ?, ?, '', 'utilisateur')
                        ");

                        $insertUser->execute([
                            $name,
                            $email,
                            $phone
                        ]);

                        $id_utilisateur = (int) $pdo->lastInsertId();
                    }


                    /* =====================================
                       8. CREER LA RESERVATION
                       ===================================== */

                    $insertReservation = $pdo->prepare("
                        INSERT INTO reservations
                        (
                            id_utilisateur,
                            id_evenement,
                            nombre_places,
                            statut,
                            date_reservation,
                            heure_debut,
                            heure_fin
                        )
                        VALUES (?, ?, ?, 'en_attente', ?, ?, ?)
                    ");


                    $insertReservation->execute([
                        $id_utilisateur,
                        $id_evenement,
                        $guests,
                        $date,
                        $heure_debut,
                        $heure_fin
                    ]);


                    /* =====================================
                       9. SESSION CLIENT
                       ===================================== */

                    $_SESSION['name'] = $name;
                    $_SESSION['email'] = $email;
                    $_SESSION['phone'] = $phone;
                    $_SESSION['guests'] = $guests;
                    $_SESSION['date'] = $date;
                    $_SESSION['heure_debut'] = $heure_debut;
                    $_SESSION['heure_fin'] = $heure_fin;

                    /*
                     * On garde l'ID de l'événement qui vient
                     * réellement d'être réservé.
                     */
                    $_SESSION['id_evenement'] = $id_evenement;

                    $_SESSION['event'] = $event['nom'];

                    $_SESSION['reservation_status'] = 'en_attente';


                    /* =====================================
                       10. REDIRECTION
                       ===================================== */

                    header("Location: profil.php");
                    exit;
                }
            }
        }
    }
}

?>

<!DOCTYPE html>

<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Réservation événement</title>


    <link rel="stylesheet"
          href="bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="./bbb.css?v=3">

</head>


<body>


<form method="POST"
      action="reservation.php?id=<?= (int) $id_evenement ?>">


    <!-- =====================================================
         ID EVENEMENT
         ===================================================== -->

    <input
        type="hidden"
        name="id_evenement"
        value="<?= (int) $id_evenement ?>"
    >


    <div class="container">


        <!-- =================================================
             HEADER
             ================================================= -->

        <div class="rheader">

            <h2>
                Event reservation form
            </h2>


            <?php if (!empty($message)): ?>

                <p style="color: red; font-weight: bold;">

                    <?= htmlspecialchars($message) ?>

                </p>

            <?php endif; ?>


            <!-- Afficher l'événement réservé -->

            <p>

                <strong>Événement :</strong>

                <?= htmlspecialchars($event['nom']) ?>

            </p>

        </div>



        <div class="row form-row">


            <!-- =================================================
                 COLONNE GAUCHE
                 ================================================= -->

            <div class="col-md-6">


                <!-- NOM -->

                <div class="formgroup">

                    <label>Fullname</label>

                    <input
                        type="text"
                        class="form"
                        name="name"
                        placeholder="Enter your full name"
                        required
                    >

                </div>



                <!-- EMAIL -->

                <div class="formgroup">

                    <label>Email</label>

                    <input
                        type="email"
                        class="form"
                        name="email"
                        placeholder="Enter Email"
                        required
                    >

                </div>



                <!-- TELEPHONE -->

                <div class="formgroup">

                    <label>Phone</label>

                    <input
                        type="text"
                        class="form"
                        name="phone"
                        placeholder="Enter your number"
                        required
                    >

                </div>



                <!-- NOMBRE DE PERSONNES -->

                <div class="formgroup">

                    <label>Number of guests</label>

                    <select
                        class="form"
                        name="guests"
                        required
                    >

                        <option value="">
                            Select number of guests
                        </option>

                        <option value="1">
                            1 Guest
                        </option>

                        <option value="2">
                            2 Guests
                        </option>

                        <option value="3">
                            3 Guests
                        </option>

                        <option value="4">
                            4 Guests
                        </option>

                        <option value="5">
                            5 Guests
                        </option>

                        <option value="6">
                            6 Guests
                        </option>

                    </select>

                </div>

            </div>



            <!-- =================================================
                 COLONNE DROITE
                 ================================================= -->

            <div class="col-md-6">


                <!-- DATE -->

                <div class="formgroup">

                    <label>Event date</label>

                    <input
                        type="date"
                        class="form"
                        name="date"
                        required
                    >

                </div>



                <!-- HEURE DEBUT -->

                <div class="formgroup">

                    <label>Start time</label>

                    <input
                        type="time"
                        class="form"
                        name="heure_debut"
                        required
                    >

                </div>



                <!-- HEURE FIN -->

                <div class="formgroup">

                    <label>End time</label>

                    <input
                        type="time"
                        class="form"
                        name="heure_fin"
                        required
                    >

                </div>



                <!-- TYPE EVENEMENT -->

                <div class="formgroup">

                    <label>Event type</label>

                    <select
                        class="form"
                        name="event"
                        required
                    >

                        <option value="">
                            Select Event type
                        </option>

                        <option value="Conference">
                            Conference
                        </option>

                        <option value="Wedding">
                            Wedding
                        </option>

                        <option value="Birthday">
                            Birthday
                        </option>

                        <option value="Concert">
                            Concert
                        </option>

                        <option value="Workshop">
                            Workshop
                        </option>

                        <option value="Seminar">
                            Seminar
                        </option>

                        <option value="Festival">
                            Festival
                        </option>

                        <option value="Party">
                            Party
                        </option>

                    </select>

                </div>



                <!-- DEMANDES SPECIALES -->

                <div class="formgroup">

                    <label>Special requests</label>

                    <textarea
                        class="form"
                        name="requests"
                        rows="4"
                        placeholder="Any special requests?"
                        maxlength="300"
                    ></textarea>

                </div>

            </div>

        </div>



        <!-- =================================================
             BOUTON
             ================================================= -->

        <button
            type="submit"
            name="submit"
            class="btn-submit"
        >

            Submit reservation

        </button>


        <div>

            <a href="historique.php">
                Historique
            </a>

        </div>


    </div>

</form>


</body>

</html>
```
