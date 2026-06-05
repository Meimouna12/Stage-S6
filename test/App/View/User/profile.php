<h1>Mon espace adhérent</h1>

<p>
    Bonjour
    <?= htmlspecialchars($user['prenom']) ?>
    <?= htmlspecialchars($user['nom']) ?>
</p>

<p>Email : <?= htmlspecialchars($user['email']) ?></p>

<p>Rôle : <?= htmlspecialchars($user['role']) ?></p>

<a href="index.php?action=logout">
    Déconnexion
</a>