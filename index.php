<?php
session_start();
include("connexion.php");

$sql = $pdo->query("
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
    <title>Evently - Organisation d'événements</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7f7;
            color: #1c3034;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button,
        input,
        select {
            font-family: inherit;
        }
        .header {
            height: 76px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            border-bottom: 1px solid #e9eeee;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 28px;
            font-weight: 700;
            color: #173438;
        }

        .navbar {
            display: flex;
            align-items: center;
            gap: 42px;
        }

        .navbar a {
            color: #4d5b61;
            font-size: 15px;
            padding: 28px 0;
            transition: 0.2s;
        }

        .navbar a:hover,
        .navbar a.active {
            color: #20594f;
            font-weight: 600;
            border-bottom: 3px solid #28665a;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .btn-login,
        .btn-register {
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-login {
            border: 1px solid #294d4b;
            color: #203b3d;
            background: white;
        }

        .btn-register {
            background: #285e54;
            color: white;
        }

        .btn-login:hover {
            background: #eef4f2;
        }

        .btn-register:hover {
            background: #1e4b43;
        }
        .hero {
            min-height: 370px;
            position: relative;
            background-image: url("images/hero-event.jpg");
            background-size: cover;
            background-position: center;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                90deg,
                rgba(13, 35, 35, 0.92),
                rgba(20, 45, 43, 0.65),
                rgba(20, 45, 43, 0.15)
            );
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 1000px;
            padding: 55px 6%;
            color: white;
        }

        .hero-small {
            font-size: 13px;
            letter-spacing: 2px;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .hero h1 {
            font-size: 42px;
            line-height: 1.18;
            margin-bottom: 18px;
        }

        .hero-description {
            font-size: 17px;
            line-height: 1.7;
            max-width: 590px;
            margin-bottom: 25px;
        }
        .search-box {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            max-width: 920px;
            min-height: 60px;
            color: #26383d;
        }

        .search-input,
        .search-select {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 18px;
            height: 60px;
            border-right: 1px solid #e1e6e6;
        }

        .search-input {
            flex: 1.6;
        }

        .search-select {
            flex: 1;
        }

        .search-box input,
        .search-box select {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            color: #526267;
            font-size: 14px;
            min-width: 0;
        }

        .search-box input::placeholder {
            color: #8b969b;
        }

        .btn-search {
            height: 48px;
            margin: 6px;
            padding: 0 25px;
            border: none;
            border-radius: 10px;
            background: #347568;
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-search:hover {
            background: #245b51;
        }
        .events-section {
            padding: 42px 3%;
            background: #f7f9f9;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .section-header h2 {
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .green-line {
            width: 32px;
            height: 3px;
            background: #33786b;
            display: inline-block;
        }

        .section-header p {
            color: #69797e;
            margin-top: 7px;
            margin-left: 47px;
            font-size: 14px;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 18px;
        }

        .event-card {
            background: white;
            border: 1px solid #e5e9e9;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(20, 50, 50, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .event-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(20, 50, 50, 0.09);
        }

        .event-image {
            height: 155px;
            position: relative;
            overflow: hidden;
        }

        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .event-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            background: white;
            color: #263b40;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .event-info {
            padding: 15px;
        }

        .event-info h3 {
            font-size: 16px;
            margin-bottom: 14px;
            color: #172e35;
            min-height: 19px;
        }

        .event-detail {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #6b7b80;
            font-size: 11px;
            margin-bottom: 10px;
        }

        .event-detail span {
            color: #326b61;
            font-size: 15px;
            min-width: 15px;
        }

        .details-btn {
            display: block;
            background: #347568;
            color: white;
            text-align: center;
            padding: 11px 8px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 16px;
            transition: 0.2s;
        }

        .details-btn:hover {
            background: #245b51;
        }
        .advantages {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            background: #f0f5f5;
            padding: 28px 6%;
            gap: 25px;
        }

        .advantage {
            display: flex;
            align-items: center;
            gap: 15px;
            border-right: 1px solid #d6e0df;
        }

        .advantage:last-child {
            border-right: none;
        }

        .advantage-icon {
            font-size: 32px;
            color: #28665a;
        }

        .advantage h4 {
            font-size: 13px;
            margin-bottom: 6px;
        }

        .advantage p {
            font-size: 11px;
            color: #78878b;
        }
        .footer {
            background: #173438;
            color: white;
            padding: 25px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-logo {
            font-size: 23px;
            font-weight: 700;
        }

        .footer p {
            font-size: 12px;
            color: #b7c7c7;
        }
        @media (max-width: 1100px) {
            .navbar {
                gap: 20px;
            }

            .events-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .advantages {
                grid-template-columns: repeat(2, 1fr);
            }

            .advantage {
                border-right: none;
            }
        }
        @media (max-width: 700px) {
            .header {
                height: auto;
                padding: 18px 5%;
                flex-wrap: wrap;
                gap: 18px;
            }

            .navbar {
                order: 3;
                width: 100%;
                justify-content: center;
                gap: 18px;
            }

            .navbar a {
                padding: 5px 0;
                font-size: 13px;
            }

            .header-actions {
                gap: 7px;
            }

            .btn-login,
            .btn-register {
                padding: 10px 13px;
                font-size: 12px;
            }

            .hero-content {
                padding: 40px 5%;
            }

            .hero h1 {
                font-size: 30px;
            }

            .hero-description {
                font-size: 14px;
            }

            .section-header {
                align-items: flex-start;
                gap: 15px;
            }

            .section-header h2 {
                font-size: 23px;
            }

            .section-header p {
                margin-left: 0;
            }

            .events-grid {
                grid-template-columns: 1fr;
            }

            .event-image {
                height: 200px;
            }

            .advantages {
                grid-template-columns: 1fr;
            }

            .footer {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
        @media (max-width: 400px) {
            .header-actions {
                width: 100%;
                justify-content: center;
            }

            .hero h1 {
                font-size: 27px;
            }

            .hero-description {
                font-size: 13px;
            }

            .section-header h2 {
                font-size: 21px;
            }
        }
    </style>
</head>

<body>

    <header class="header">
        <a href="index.php" class="logo">
            Evently
        </a>

        <nav class="navbar">
            <a href="accueil.php" class="active">Accueil</a>
            <a href="#evenements">Événements</a>
            <a href="contact.php">Contact</a>
        </nav>

        <div class="header-actions">
            <a href="Cox.php" class="btn-login">Se connecter</a>
            <a href="inscription.php" class="btn-register">S'inscrire</a>
            <a href="admin.php" class="btn-register">Admin</a>
        </div>
    </header>

    <section class="hero">
        <div class="hero-overlay"></div>

        <div class="hero-content">
            <p class="hero-small">RÉSERVEZ, ORGANISEZ, CÉLÉBREZ</p>

            <h1>
                Trouvez la date parfaite<br>
                pour votre événement
            </h1>

            <p class="hero-description">
                Découvrez et réservez facilement une date
                pour organiser vos événements :
                mariages, anniversaires, séminaires, ateliers...
            </p>
        </div>
    </section>

    <section class="events-section" id="evenements">
        <div class="section-header">
            <div>
                <h2>
                    <span class="green-line"></span>
                    Événements populaires
                </h2>
                <p>
                    Découvrez les événements les plus réservés
                    et trouvez l'inspiration pour le vôtre.
                </p>
            </div>
        </div>

        <div class="events-grid">
            <?php while ($event = $sql->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="event-card">
                    <div class="event-image">
                        <img src="<?= htmlspecialchars($event['image']) ?>" alt="<?= htmlspecialchars($event['nom']) ?>">
                        <span class="event-badge"><?= htmlspecialchars($event['type']) ?></span>
                    </div>

                    <div class="event-info">
                        <h3><?= htmlspecialchars($event['nom']) ?></h3>

                        <p class="event-detail">
                            <span>▣</span>
                            <?= htmlspecialchars($event['date_evenement']) ?>
                        </p>

                        <p class="event-detail">
                            <span>⌖</span>
                            <?= htmlspecialchars($event['lieu']) ?>
                        </p>

                        <p class="event-detail">
                            <span>◷</span>
                            <?= date('H:i', strtotime($event['heure_debut'])) ?> — <?= date('H:i', strtotime($event['heure_fin'])) ?>
                        </p>

                        <a href="details.php?id=<?= (int) $event['id_evenement'] ?>" class="details-btn">
                            Voir détails →
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>

    <section class="advantages" id="apropos">
        <div class="advantage">
            <span class="advantage-icon">▣</span>
            <div>
                <h4>Réservation facile</h4>
                <p>En quelques clics seulement</p>
            </div>
        </div>

        <div class="advantage">
            <span class="advantage-icon">♢</span>
            <div>
                <h4>Événements vérifiés</h4>
                <p>Pour une expérience en toute confiance</p>
            </div>
        </div>

        <div class="advantage">
            <span class="advantage-icon">♧</span>
            <div>
                <h4>Notifications</h4>
                <p>Restez informé en temps réel</p>
            </div>
        </div>

        <div class="advantage">
            <span class="advantage-icon">◉</span>
            <div>
                <h4>Support 24/7</h4>
                <p>Nous sommes là pour vous</p>
            </div>
        </div>
    </section>

    <footer class="footer" id="contact">
        <div class="footer-logo">Evently</div>
        <p>© 2026 Evently — Réservez, Organisez, Célébrez.</p>
    </footer>

</body>
</html>