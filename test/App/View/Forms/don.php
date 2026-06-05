<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Faire un don - All Inclusive</title>
    <link rel="stylesheet" href="assets/css/site.css">
    <style>
        .form-wrap { padding: 2rem 0; }
        form { max-width: 620px; margin: 0 auto; display: grid; gap: 0.8rem; }
        .amounts { display:flex; gap:0.6rem; flex-wrap:wrap; }
        .amounts label { background:#fff; border:1px solid #d8d0c2; padding:0.6rem 0.8rem; border-radius:8px; cursor:pointer; }
        input[type="radio"] { margin-right:0.4rem; }
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
        <h1>Faire un don</h1>
        <p>Choisissez un montant ou indiquez un montant personnalisé.</p>

        <form method="post" action="index.php?action=don">
            <label>Nom
                <input type="text" name="name" required>
            </label>
            <label>Email
                <input type="email" name="email" required>
            </label>

            <div class="amounts">
                <label><input type="radio" name="amount" value="10">10€</label>
                <label><input type="radio" name="amount" value="25">25€</label>
                <label><input type="radio" name="amount" value="50">50€</label>
                <label><input type="radio" name="amount" value="100">100€</label>
                <label>Autre: <input type="text" name="amount_custom" placeholder="Montant en €"></label>
            </div>

            <button class="btn" type="submit">Poursuivre le don</button>
        </form>
    </main>

    <footer class="site-footer">
        <div class="container footer-bottom">© <?= date('Y') ?> All Inclusive</div>
    </footer>
</body>
</html>
