<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Modifier utilisateur</title>

<style>

body{
    background:#f4f6fb;
    font-family:'Segoe UI',sans-serif;
}

.container{
    max-width:800px;
    margin:40px auto;
    background:white;
    padding:40px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

h1{
    margin-bottom:30px;
    color:#123847;
}

.form-group{
    margin-bottom:20px;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
}

input,
select{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:10px;
    font-size:15px;
}

.buttons{
    margin-top:30px;
    display:flex;
    gap:15px;
}

.btn-save{
    background:#123847;
    color:white;
    text-decoration:none;
    border:none;
    padding:12px 25px;
    border-radius:10px;
    cursor:pointer;
}

.btn-back{
    background:#e5e7eb;
    color:#111827;
    text-decoration:none;
    padding:12px 25px;
    border-radius:10px;
}

.btn-save:hover{
    opacity:.9;
}

</style>
</head>

<body>

<div class="container">

<h1> Modifier utilisateur</h1>

<form method="post" action="index.php?action=user_update">

    <input type="hidden"
           name="id"
           value="<?= $user['id'] ?>">

    <div class="form-group">
        <label>Nom</label>
        <input
            type="text"
            name="nom"
            value="<?= htmlspecialchars($user['nom']) ?>"
            required>
    </div>

    <div class="form-group">
        <label>Prénom</label>
        <input
            type="text"
            name="prenom"
            value="<?= htmlspecialchars($user['prenom']) ?>"
            required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input
            type="email"
            name="email"
            value="<?= htmlspecialchars($user['email']) ?>"
            required>
    </div>

    <div class="form-group">
        <label>Rôle</label>

        <select name="role">

            <option value="admin"
                <?= $user['role']=='admin' ? 'selected' : '' ?>>
                Admin
            </option>

            <option value="referent"
                <?= $user['role']=='referent' ? 'selected' : '' ?>>
                Référent
            </option>

            <option value="adherent"
                <?= $user['role']=='adherent' ? 'selected' : '' ?>>
                Adhérent
            </option>

        </select>
    </div>

    <div class="form-group">
        <label>Antenne</label>

        <select name="antenne_id">

            <option value="">
                Aucune antenne
            </option>

            <pre>
<?php print_r($antennes); ?>
</pre>
            <?php foreach($antennes as $antenne): ?>

                <option
                    value="<?= $antenne['id'] ?>"
                    <?= ($user['antenne_id'] == $antenne['id']) ? 'selected' : '' ?>>

                    <?= htmlspecialchars($antenne['nom']) ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <div class="buttons">

        <button class="btn-save">
             Enregistrer
        </button>

        <a
            href="index.php?action=users"
            class="btn-back">
            ← Retour
        </a>

    </div>

</form>

</div>

</body>
</html>