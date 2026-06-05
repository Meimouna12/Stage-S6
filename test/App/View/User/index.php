<!DOCTYPE html>
<html>
<head>
    <title>Test rôles utilisateurs</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 12px; margin-bottom: 10px; }
        .meta { color: #666; font-size: 14px; }
        form { margin-bottom: 20px; }
        label { display: block; margin-top: 8px; }
    </style>
</head>
<body>

<h1>Test de visibilité par rôle</h1>

<form method="get" action="index.php">
    <input type="hidden" name="action" value="users">

    <label>
        Rôle
        <select name="role">
            <option value="admin" <?= $currentRole === 'admin' ? 'selected' : '' ?>>admin</option>
            <option value="referent" <?= $currentRole === 'referent' ? 'selected' : '' ?>>referent</option>
            <option value="adherent" <?= $currentRole === 'adherent' ? 'selected' : '' ?>>adherent</option>
        </select>
    </label>

    <label>
        Antenne ID
        <input type="number" name="antenne_id" value="<?= htmlspecialchars((string) ($currentAntenneId ?? '')) ?>">
    </label>

    <p class="meta">Pour tester le cas referent, utilise une antenne ID existante comme 1, 2 ou 3.</p>

    <button type="submit">Tester</button>
</form>

<?php if (empty($users)): ?>
    <p>Aucun utilisateur visible avec ces paramètres.</p>
<?php else: ?>
    <?php foreach ($users as $user): ?>
        <div class="card">
            <strong><?= htmlspecialchars(trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''))) ?></strong>
            <div class="meta"><?= htmlspecialchars($user['email'] ?? '') ?></div>
            <div class="meta">Rôle : <?= htmlspecialchars($user['role'] ?? '') ?></div>
            <div class="meta">Antenne : <?= htmlspecialchars($user['antenne_nom'] ?? 'Aucune') ?></div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<p><a href="index.php">Retour aux antennes</a></p>

</body>
</html>