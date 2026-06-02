<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Don reçu</title>
    <link rel="stylesheet" href="assets/css/site.css">
</head>
<body>
    <main class="container section">
        <h1>Merci pour votre don</h1>
        <p>Merci <?= htmlspecialchars($name ?? '') ?> — nous avons bien reçu votre don de <?= htmlspecialchars($amount ?? '') ?>€ (simulation).</p>
        <p>Un e‑mail de confirmation sera envoyé à <?= htmlspecialchars($email ?? '') ?>.</p>
        <p><a href="index.php">Retour à l'accueil</a></p>
    </main>
</body>
</html>
