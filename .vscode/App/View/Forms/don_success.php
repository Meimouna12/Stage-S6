<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Merci pour votre don</title>
    <link rel="stylesheet" href="assets/css/site.css">
</head>
<body>

<main class="container section">

    <h1>Merci pour votre don ❤️</h1>

    <p>
        Votre don de
        <strong><?= htmlspecialchars($amount) ?> €</strong>
        a bien été enregistré.
    </p>

    <p>
        Email : <?= htmlspecialchars($email) ?>
    </p>

    <?php if (!empty($id)): ?>
        <p>Référence : <?= (int)$id ?></p>
    <?php endif; ?>

    <a href="index.php" class="btn">
        Retour à l'accueil
    </a>

</main>

</body>
</html>