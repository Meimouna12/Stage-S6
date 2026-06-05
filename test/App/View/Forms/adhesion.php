<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Adhésion - All Inclusive</title>
	<link rel="stylesheet" href="assets/css/site.css">
		<style>
			.form-wrap { padding: 2rem 0; }
			form { max-width: 720px; margin: 0 auto; display: grid; gap: 0.8rem; }
			label { display:block; font-weight:600; }
			input[type="text"], input[type="email"], select { padding: 0.6rem; border-radius:6px; border:1px solid #d8d0c2; }
			.alert { background:#fff4e5; border:1px solid #f0c36d; color:#6a4b00; padding:0.75rem 0.9rem; border-radius:8px; margin:0 0 1rem; }
		</style>
</head>
<body>
	<header class="hero">
		<div class="hero__texture"></div>
		<nav class="topbar container" aria-label="Navigation principale">
			<div class="brand">
				<a href="index.php" style="display:inline-flex;align-items:center;gap:0.6rem;text-decoration:none;color:inherit;">
					<img src="assets/logo/logo%20du%20site.jpg" alt="All Inclusive" class="site-logo">
					<span>All Inclusive</span>
				</a>
			</div>
		</nav>
	</header>

	<main class="container form-wrap">
		<?php if (!empty($error)): ?>
			<div class="alert"><?= htmlspecialchars($error) ?></div>
		<?php endif; ?>
		<h1>Formulaire d'adhésion</h1>
		<p>Remplissez le formulaire pour devenir adhérent. Le paiement Stripe de <strong>30 €</strong> se fera avant l’envoi final.</p>

		<form method="post" action="index.php?action=adhesion">
			<div class="form-row">
				<div class="col">
					<label>Prénom
						<input type="text" name="prenom" required value="<?= htmlspecialchars($old['prenom'] ?? '') ?>">
					</label>
				</div>
				<div class="col">
					<label>Nom
						<input type="text" name="nom" required value="<?= htmlspecialchars($old['nom'] ?? '') ?>">
					</label>
				</div>
			</div>

			<label>Email
				<input type="email" name="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
			</label>
			<label>Antenne
				<select name="antenne">
					<option value="">-- Sélectionnez --</option>
					<option value="Antenne 1" <?= (($old['antenne'] ?? '') === 'Antenne 1') ? 'selected' : '' ?>>Antenne 1 : Saint-Denis</option>
					<option value="Antenne 2" <?= (($old['antenne'] ?? '') === 'Antenne 2') ? 'selected' : '' ?>>Antenne 2 : Valenton</option>
					<option value="Antenne 3" <?= (($old['antenne'] ?? '') === 'Antenne 3') ? 'selected' : '' ?>>Antenne 3 : Villiers-sur-Marne</option>
					<option value="Antenne 4" <?= (($old['antenne'] ?? '') === 'Antenne 4') ? 'selected' : '' ?>>Antenne 4 : Paris</option>
					<option value="Antenne 5" <?= (($old['antenne'] ?? '') === 'Antenne 5') ? 'selected' : '' ?>>Antenne 5 : Gif-sur-Yvette</option>
					<option value="Antenne 6" <?= (($old['antenne'] ?? '') === 'Antenne 6') ? 'selected' : '' ?>>Antenne 6 : Epinay-sur-Seine</option>
                    <option value="Antenne 7" <?= (($old['antenne'] ?? '') === 'Antenne 7') ? 'selected' : '' ?>>Antenne 7 : Villetaneuse</option>
                    <option value="Antenne 8" <?= (($old['antenne'] ?? '') === 'Antenne 8') ? 'selected' : '' ?>>Antenne 8 : Sevran</option>


				</select>
			</label>

			<input type="hidden" name="montant" value="30">
			<button class="btn" type="submit">Payer 30 € avec Stripe</button>
		</form>
	</main>

	<footer class="site-footer">
		<div class="container footer-bottom">© <?= date('Y') ?> All Inclusive</div>
	</footer>
</body>
</html>

