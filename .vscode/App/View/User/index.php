<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs</title>

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Space Grotesk',sans-serif;
            background:#f5f7fb;
            color:#222;
        }

        .container{
            max-width:1200px;
            margin:auto;
            padding:40px;
        }

        .header{
            background:white;
            padding:25px 35px;
            border-radius:20px;
            margin-bottom:30px;
            box-shadow:0 10px 25px rgba(0,0,0,.08);
        }

        h1{
            font-family:'Fraunces',serif;
            font-size:2.5rem;
            color:#2c3e50;
        }

        .subtitle{
            color:#666;
            margin-top:10px;
        }

       .actions{
    display:flex;
    gap:10px;
}

.btn-edit{
    background:#2563eb;
    color:white;
    text-decoration:none;
    padding:8px 14px;
    border-radius:10px;
    font-weight:600;
    transition:0.3s;
    display:inline-flex;
    align-items:center;
    gap:6px;
}

.btn-edit:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

.btn-delete{
    background:#ef4444;
    color:white;
    text-decoration:none;
    padding:8px 14px;
    border-radius:10px;
    font-weight:600;
    transition:0.3s;
    display:inline-flex;
    align-items:center;
    gap:6px;
}

.btn-delete:hover{
    background:#dc2626;
    transform:translateY(-2px);
}
        .table-card{
            background:white;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 10px 25px rgba(0,0,0,.08);
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        thead{
            background:#2563eb;
            color:white;
        }

        th{
            text-align:left;
            padding:18px;
        }

        td{
            padding:18px;
            border-bottom:1px solid #eee;
        }

        tr:hover{
            background:#f8fafc;
        }

        .badge{
            padding:6px 12px;
            border-radius:50px;
            font-size:.85rem;
            font-weight:bold;
        }

        .admin{
            background:#fee2e2;
            color:#dc2626;
        }

        .referent{
            background:#dbeafe;
            color:#2563eb;
        }

        .adherent{
            background:#dcfce7;
            color:#16a34a;
        }

        .empty{
            text-align:center;
            padding:40px;
            color:#777;
        }

        .top-links{
            margin-bottom:25px;
        }

        .top-links a{
            text-decoration:none;
            color:#2563eb;
            font-weight:600;
            margin-right:20px;
        }

        .top-links a:hover{
            text-decoration:underline;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="top-links">
        <a href="?action=dashboard">← Dashboard</a>
        <a href="?action=logout">Déconnexion</a>
    </div>

    <div class="header">
        <h1>Gestion des utilisateurs</h1>
        <p class="subtitle">
            Liste des utilisateurs visibles selon ton rôle.
        </p>
    </div>

    <div class="table-card">

        <?php if(!empty($users)): ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Antenne</th>
                    <th>Actions</th>

                </tr>
            </thead>

            <tbody>

            <?php foreach($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    <td><?= htmlspecialchars($user['prenom']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>

                    <td>
                        <span class="badge <?= $user['role'] ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </td>

                    <td>
                        <?= htmlspecialchars($user['antenne_nom'] ?? '-') ?>
                    </td>

                    <td>
                    <a class="btn-edit"
                        href="index.php?action=user_edit&id=<?= $user['id'] ?>">
                        Modifier
                    </a>

                    <a class="btn-delete"
                         href="index.php?action=user_delete&id=<?= $user['id'] ?>"
                        onclick="return confirm('Supprimer cet utilisateur ?')">
                        Supprimer
                    </a>
                    </td>
                </tr>
            <?php endforeach; ?>

            </tbody>
        </table>

        <?php else: ?>

            <div class="empty">
                Aucun utilisateur trouvé.
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>