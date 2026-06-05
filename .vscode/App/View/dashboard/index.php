<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Administration - All Inclusive</title>

<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Space Grotesk',sans-serif;
    background:#f6f8fb;
    color:#1f2937;
}

.layout{
    display:flex;
    min-height:100vh;
}

/* SIDEBAR */

.sidebar{
    width:260px;
    background:#123847;
    color:white;
    padding:30px 20px;
}

.sidebar h1{
    font-family:'Fraunces',serif;
    font-size:1.8rem;
    margin-bottom:40px;
}

.sidebar-menu{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.sidebar-menu a{
    color:white;
    text-decoration:none;
    padding:14px 16px;
    border-radius:12px;
    transition:.3s;
}

.sidebar-menu a:hover{
    background:rgba(255,255,255,.1);
}

.logout{
    margin-top:40px;
    display:block;
    text-align:center;
    background:#e56b3d;
}

.logout:hover{
    background:#d85c2c !important;
}

/* CONTENU */

.main{
    flex:1;
    padding:40px;
}

.welcome{
    background:white;
    padding:30px;
    border-radius:20px;
    margin-bottom:35px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.welcome h2{
    font-family:'Fraunces',serif;
    margin-bottom:10px;
}

.role-badge{
    display:inline-block;
    margin-top:10px;
    background:#dbeafe;
    color:#2563eb;
    padding:8px 15px;
    border-radius:999px;
    font-weight:bold;
}

/* STATS */

.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:25px;
    margin-bottom:40px;
}

.card{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    transition:.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card h3{
    color:#6b7280;
    margin-bottom:15px;
}

.number{
    font-size:2.4rem;
    font-weight:bold;
    color:#123847;
}

/* MENU */

.menu-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:25px;
}

.menu-card{
    background:white;
    border-radius:20px;
    padding:30px;
    text-decoration:none;
    color:#1f2937;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    transition:.3s;
}

.menu-card:hover{
    transform:translateY(-5px);
}

.menu-card h3{
    margin-bottom:10px;
    color:#123847;
}

.menu-card p{
    color:#6b7280;
}

</style>
</head>

<body>

<div class="layout">

    <aside class="sidebar">

        <h1>All Inclusive</h1>

        <div class="sidebar-menu">
            <a href="index.php">🌐 Retour au site</a>

            <a href="?action=dashboard">🏠 Dashboard</a>

            <a href="?action=users">👥 Utilisateurs</a>

            <a href="?action=antennes">📍 Antennes</a>

            <a href="?action=actualites">📰 Actualités</a>

            <a href="?action=evenements">📅 Événements</a>

            <a href="?action=dons">💰 Dons</a>

            <a href="?action=adhesions">📋 Adhésions</a>

            <a class="logout" href="?action=logout">
                Déconnexion
            </a>

        </div>

    </aside>

    <main class="main">
 <?php
    $prenom = $_SESSION['user']['prenom'] ?? 'Administrateur';
    $role = $_SESSION['user']['role'] ?? 'admin';
?>

<div class="welcome">

    <h2>
        Bonjour <?= htmlspecialchars($prenom) ?>
    </h2>

    <p>
        Bienvenue dans l'espace d'administration.
    </p>

    <span class="role-badge">
        <?= strtoupper(htmlspecialchars($role)) ?>
    </span>

</div>

        <div class="stats-grid">

            <div class="card">
                <h3>👥 Utilisateurs</h3>
                <div class="number"><?= $nbUsers ?></div>
            </div>

            <div class="card">
                <h3>📍 Antennes</h3>
                <div class="number"><?= $nbAntennes ?></div>
            </div>

            <div class="card">
                <h3>📰 Actualités</h3>
                <div class="number"><?= $nbActualites ?></div>
            </div>

            <div class="card">
                <h3>📅 Événements</h3>
                <div class="number"><?= $nbEvenements ?></div>
            </div>

            <div class="card">
                <h3>💰 Dons</h3>
                <div class="number"><?= $nbDons ?></div>
            </div>

        </div>

        <div class="menu-grid">

            <a href="?action=users" class="menu-card">
                <h3>👥 Gestion des utilisateurs</h3>
                <p>Administrer les adhérents, référents et administrateurs.</p>
            </a>

            <a href="?action=antennes" class="menu-card">
                <h3>📍 Gestion des antennes</h3>
                <p>Consulter et modifier les antennes locales.</p>
            </a>

            <a href="?action=actualites" class="menu-card">
                <h3>📰 Actualités</h3>
                <p>Publier les dernières informations de l'association.</p>
            </a>

            <a href="?action=evenements" class="menu-card">
                <h3>📅 Événements</h3>
                <p>Créer et gérer les événements.</p>
            </a>

           <a href="index.php?action=dons_admin" class="menu-card">
             <div class="card-icon">💰</div>
             <h3>Dons</h3>
             <p>Suivre les dons reçus.</p>
            </a>

            <a href="index.php?action=adhesions_admin" class="menu-card">
                <div class="card-icon">📋</div>
                <h3>Adhésions</h3>
                <p>Suivre les adhésions des membres.</p>
            </a>

        </div>

    </main>

</div>

</body>
</html>