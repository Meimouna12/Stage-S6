<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Adhésion reçue</title>
    <link rel="stylesheet" href="assets/css/site.css">
</head>
<body>
    <main class="container section">
        <h1>Merci pour votre adhésion</h1>
        <p>Merci <?= htmlspecialchars(($prenom ?? '') . ' ' . ($nom ?? '')) ?> — nous avons bien reçu votre demande d'adhésion.</p>
        <p>Un e‑mail de confirmation sera envoyé à <?= htmlspecialchars($email ?? '') ?>.</p>
        <p>Montant de l'adhésion : <strong><?= htmlspecialchars(number_format(($montant ?? 0), 2, ',', ' ')) ?> €</strong></p>
        <?php if (!empty($id)): ?>
            <p>Référence d'adhésion : <?= (int) $id ?></p>
        <?php endif; ?>
        <p><a href="index.php">Retour à l'accueil</a></p>
    </main>
</body>
</html>
