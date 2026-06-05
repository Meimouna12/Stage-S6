<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Inclusive - Accueil</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/site.css">
</head>
<body>
    <header class="hero">
        <div class="hero__texture"></div>
        <nav class="topbar container" aria-label="Navigation principale">
            <div class="brand">
                <img src="assets/logo/logo%20du%20site.jpg" alt="All Inclusive" class="site-logo">
                All Inclusive
            </div>
            <ul class="nav-links" role="menubar">
                <li role="none"><a role="menuitem" href="index.php" class="nav-link">Accueil</a></li>
                <li role="none"><a role="menuitem" href="index.php?action=antennes" class="nav-link">Nos antennes</a></li>
                <li role="none"><a role="menuitem" href="#contact" class="nav-link">Contact</a></li>
            </ul>
        </nav>

        <div class="hero__content container reveal">
            <p class="eyebrow">Association & territoires</p>
            <h1>Bienvenue chez All Inclusive</h1>
            <p class="hero__lead">Nous soutenons les familles avec des antennes locales proches de vous — découvrez nos actions et rejoignez-nous.</p>
            <p style="margin-top:1.2rem;"><a class="btn" href="index.php?action=antennes">Découvrir les antennes</a></p>
        </div>
    </header>

    <main class="container section">

        <section class="section origin-section">
            <div class="section__head reveal">
                <h2>ORIGINE ET CONTEXTE</h2>
            </div>

            <div class="detail">
                <p>L’association naît en 2018 à l’initiative de Lynda Fekiri, présidente de l’association All Inclusive, à partir d’un vécu de terrain et d’un engagement concret pour faire évoluer l’accès aux droits, aux activités et à la société pour les enfants autistes et leurs familles.</p>

                <h3>OBJECTIFS :</h3>
                <ul>
                    <li>Permettre un accès réel et équitable aux activités sportives, culturelles et de loisirs pour les enfants autistes et leurs familles</li>
                    <li>Adapter les environnements plutôt que chercher à normaliser les enfants, en tenant compte des besoins sensoriels, communicationnels et fonctionnels</li>
                    <li>Accompagner les familles dans leurs démarches administratives, juridiques et leur quotidien, face à des systèmes souvent inadaptés</li>
                    <li>Développer des espaces inclusifs où enfants, parents et fratries peuvent participer ensemble, sans exclusion</li>
                    <li>Soutenir l’autonomie et le pouvoir d’agir des personnes autistes, en respectant leur fonctionnement et leurs singularités</li>
                    <li>Sensibiliser sans caricaturer, pour favoriser une meilleure compréhension et une inclusion concrète dans la société</li>
                </ul>
            </div>
        </section>

        <div class="section__head reveal">
            <h2>Nos missions</h2>
            <p>All Inclusive organise des activités de soutien, de soutien scolaire et d'accompagnement social.</p>
        </div>

        <div class="cards">
            <article class="card reveal">
                <img src="assets/img/image.jpg" alt="Accompagnement des familles" class="mission-card__image">
                <h3>Soutien familial et inclusion sociale</h3>
                <p class="card__meta">Un accompagnement de proximité pour favoriser l’accès aux activités, aux services et à l’inclusion.</p>
            </article>
            <article class="card reveal">
                <img src="assets/img/img.jpg" alt="Activités locales et soutien associatif" class="mission-card__image">
                <h3>Événements et rencontres locales</h3>
                <p class="card__meta">Ateliers, sorties et rencontres organisés par nos antennes.</p>
            </article>
            <article class="card reveal">
                <img src="assets/img/marche%20pour%20l%27autisme.jpg" alt="Marche pour l'autisme" class="mission-card__image">
                <h3>Mobilisation citoyenne</h3>
                <p class="card__meta">Ensemble, faisons progresser l’inclusion et la reconnaissance des droits de chacun.</p>
            </article>
        </div>

        <section class="section featured-news">
            <div class="section__head reveal">
                <h2>À la une</h2>
                <p>Les dernières actualités de l'association.</p>
            </div>

            <div class="cards">
                <?php if (!empty($actualites)): ?>
                    <?php foreach ($actualites as $act): ?>
                        <article class="card reveal">
                            <h3><?= htmlspecialchars($act['titre']) ?></h3>
                            <p class="card__meta"><?= htmlspecialchars($act['chapo']) ?></p>
                            <p class="meta">Publiée le <?= htmlspecialchars($act['date']) ?></p>
                            <a class="btn" href="index.php?action=actualite&slug=<?= urlencode($act['slug']) ?>">Lire</a>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucune actualité pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="section cta-section">
            <div class="cards">
                <article class="card reveal">
                    <h3>Devenez adhérent</h3>
                    <p class="card__meta">Rejoignez notre réseau et soutenez les actions locales.</p>
                    <a class="btn" href="index.php?action=adhesion">Adhérer</a>
                </article>
                <article class="card reveal">
                    <h3>Faire un don</h3>
                    <p class="card__meta">Soutenez nos projets : chaque don compte.</p>
                    <a class="btn" href="index.php?action=don">Faire un don</a>
                </article>
            </div>
        </section>
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
                    <li><a href="index.php?action=antennes">Nos antennes</a></li>
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
