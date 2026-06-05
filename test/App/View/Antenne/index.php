<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Inclusive - Nos antennes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/site.css">
</head>
<body>
    <header class="hero">
        <div class="hero__texture"></div>
        <nav class="topbar container" aria-label="Navigation principale">
            <div class="brand">All Inclusive</div>
            <ul class="nav-links" role="menubar">
                <li role="none"><a role="menuitem" href="index.php" class="nav-link">Accueil</a></li>
                <li role="none"><a role="menuitem" href="index.php?action=antennes" class="nav-link">Nos antennes</a></li>
                <li role="none"><a role="menuitem" href="index.php?action=users&role=referent&antenne_id=1" class="nav-link">Test permissions</a></li>
                <li role="none"><a role="menuitem" href="#contact" class="nav-link">Contact</a></li>
            </ul>
        </nav>
        <div class="hero__content container reveal">
            <p class="eyebrow">Association & territoires</p>
            <h1>Des antennes de proximite pour accompagner chaque famille.</h1>
            <p class="hero__lead">Decouvrez nos antennes locales, leurs equipes referentes et les points de contact utiles pour participer aux activites.</p>
        </div>
    </header>

    <main class="container section">
        <div class="section__head reveal">
            <h2>Nos antennes actives</h2>
            <p>Selectionne une antenne pour voir ses informations detaillees.</p>
        </div>

        <div class="cards">
            <?php foreach ($antennes as $a): ?>
                <article class="card reveal">
                    <div class="card__city"><?= htmlspecialchars($a['ville']) ?></div>
                    <h3><?= htmlspecialchars($a['nom']) ?></h3>
                    <p class="card__meta">Responsable: <?= htmlspecialchars($a['responsable'] ?? 'Non renseigne') ?></p>
                    <?php if (!empty($a['is_new'])): ?>
                        <p class="card__badge">Nouvelle antenne</p>
                    <?php endif; ?>
                    <a class="btn" href="index.php?action=show&id=<?= (int) $a['id'] ?>">Voir la fiche</a>
                </article>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <strong>All Inclusive</strong>
                <p>Accompagner les familles au plus près du territoire.</p>
            </div>

            <div class="footer-links">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="index.php?action=users&role=referent&antenne_id=1">Test permissions</a></li>
                </ul>
            </div>

            <div class="footer-contact" id="contact">
                <h4>Contact</h4>               
                <p> allinclusive.autisme@gmail.com</p>
                <p>+33 6 59 18 78 92</p>

            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">© <?= date('Y') ?> All Inclusive — Tous droits réservés</div>
        </div>
    </footer>

    <script src="assets/js/site.js"></script>
</body>
</html>