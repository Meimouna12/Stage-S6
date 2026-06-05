<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($antenne['nom']) ?> - All Inclusive</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <link rel="stylesheet" href="assets/css/site.css">
</head>
<body>
    <?php
    $mapQuery = trim(($antenne['adresse'] ?? '') . ' ' . ($antenne['ville'] ?? ''));
    $mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($mapQuery !== '' ? $mapQuery : ($antenne['ville'] ?? 'France'));
    $isSaintDenis = isset($antenne['ville']) && stripos($antenne['ville'], 'saint-denis') !== false;
    preg_match('/\b(\d{5})\b/', (string) ($antenne['adresse'] ?? ''), $postalMatch);
    $deptCode = '';
    if (!empty($postalMatch[1])) {
        $postal = $postalMatch[1];
        if (strpos($postal, '20') === 0) {
            $deptCode = '2A';
        } else {
            $deptCode = substr($postal, 0, 2);
        }
    }
    ?>
    <main class="antenne-page container reveal">
        <a class="back" href="index.php">Retour aux antennes</a>

        <header class="antenne-header">
            <div class="antenne-logo">
                <span>Association</span>
                <strong>All Inclusive</strong>
                <small><?= htmlspecialchars($antenne['ville'] ?? 'Paris') ?></small>
            </div>
            <h1>Antenne de <?= htmlspecialchars($antenne['ville'] ?? $antenne['nom']) ?></h1>
        </header>

        <section class="antenne-layout">
            <aside class="contact-box">
                <h2>Contact</h2>
                <div class="contact-item">
                    <span>Referente</span>
                    <strong><?= htmlspecialchars($antenne['responsable'] ?? 'Non renseigne') ?></strong>
                </div>
                <div class="contact-item">
                    <span>Numero telephone</span>
                    <strong><?= htmlspecialchars($antenne['telephone'] ?? 'Non renseigne') ?></strong>
                </div>
                <div class="contact-item">
                    <span>Email</span>
                    <strong><?= htmlspecialchars($antenne['email'] ?? 'Non renseigne') ?></strong>
                </div>
                <div class="contact-item">
                    <span>Adresse</span>
                    <strong>
                        <?= htmlspecialchars(!empty($antenne['adresse']) ? $antenne['adresse'] : ($antenne['ville'] ?? 'Non renseigne')) ?>
                    </strong>
                </div>

                <div
                    id="dept-map"
                    class="contact-map contact-map--france"
                    data-dept-code="<?= htmlspecialchars($deptCode !== '' ? $deptCode : ($isSaintDenis ? '93' : '')) ?>"
                    aria-label="Carte de France par departements"
                ></div>
                <p class="map-help">Clique sur la carte pour voir l'adresse exacte</p>
                <a class="map-link" href="<?= htmlspecialchars($mapUrl) ?>" target="_blank" rel="noopener noreferrer">Ouvrir l'adresse exacte</a>

                <?php if (!empty($socialLinks)): ?>
                    <div class="social-box">
                        <strong>Reseaux :</strong>
                        <ul class="social-list">
                            <?php foreach ($socialLinks as $social): ?>
                                <li>
                                    <a href="<?= htmlspecialchars($social['url']) ?>" target="_blank" rel="noopener noreferrer">
                                        <?= ucfirst(htmlspecialchars($social['type'])) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($mairieInfo) && (
                    !empty($mairieInfo['mairie_nom']) ||
                    !empty($mairieInfo['maire_nom']) ||
                    !empty($mairieInfo['maire_email']) ||
                    !empty($mairieInfo['mairie_tel']) ||
                    !empty($mairieInfo['cabinet_email']) ||
                    !empty($mairieInfo['adjointe_nom'])
                )): ?>
                    <div class="mairie-box">
                        <strong>Mairie :</strong>
                        <?php if (!empty($mairieInfo['mairie_nom'])): ?>
                            <p class="mairie-line"><?= htmlspecialchars($mairieInfo['mairie_nom']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($mairieInfo['maire_nom'])): ?>
                            <p class="mairie-line"><strong>Maire :</strong> <?= htmlspecialchars($mairieInfo['maire_nom']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($mairieInfo['maire_email'])): ?>
                            <p class="mairie-line"><strong>Email maire :</strong> <?= htmlspecialchars($mairieInfo['maire_email']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($mairieInfo['mairie_tel'])): ?>
                            <p class="mairie-line"><strong>Telephone mairie :</strong> <?= htmlspecialchars($mairieInfo['mairie_tel']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($mairieInfo['cabinet_email'])): ?>
                            <p class="mairie-line"><strong>Email cabinet :</strong> <?= htmlspecialchars($mairieInfo['cabinet_email']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($mairieInfo['adjointe_nom'])): ?>
                            <p class="mairie-line"><strong>Adjointe :</strong> <?= htmlspecialchars($mairieInfo['adjointe_nom']) ?><?= !empty($mairieInfo['adjointe_fonction']) ? ' - ' . htmlspecialchars($mairieInfo['adjointe_fonction']) : '' ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </aside>

            <div class="content-area">
                <article class="presentation-box">
                    <h2>Presentation de l'antenne</h2>
                    <?php if (!empty($antenne['is_new'])): ?>
                        <span class="badge-new">Nouvelle antenne</span>
                    <?php endif; ?>
                    <p>
                        <?= htmlspecialchars($antenne['description'] ?: "Cette antenne accompagne les familles, coordonne des actions locales et propose des ateliers adaptes aux besoins des adherents.") ?>
                    </p>
                </article>

                <section class="events-section">
                    <h2>Evenements</h2>
                    <div class="events-grid">
                        <?php if (!empty($events)): ?>
                            <?php foreach ($events as $event): ?>
                                <?php
                                $max = (int) ($event['places_max'] ?? 0);
                                $inscrits = (int) ($event['inscrits'] ?? 0);
                                $restantes = $max > 0 ? max($max - $inscrits, 0) : null;
                                ?>
                                <article class="event-card">
                                    <h3><?= htmlspecialchars($event['titre'] ?? 'Evenement') ?></h3>
                                    <p><?= htmlspecialchars($event['description'] ?: "Description de l'evenement") ?></p>
                                    <p class="event-meta">
                                        Date : <?= htmlspecialchars($event['date_evenement'] ? date('d/m/Y H:i', strtotime($event['date_evenement'])) : 'A definir') ?>
                                    </p>
                                    <p class="event-meta">
                                        <?= $restantes !== null ? 'Places restantes : ' . $restantes : 'Places : non limitees' ?>
                                    </p>
                                    <button type="button">S'inscrire</button>
                                </article>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <article class="event-card event-card--empty">
                                <h3>Aucun evenement a venir</h3>
                                <p>Les prochaines activites seront affichees ici.</p>
                                <button type="button" disabled>Bientot</button>
                            </article>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </section>
    </main>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="assets/js/site.js"></script>
</body>
</html>