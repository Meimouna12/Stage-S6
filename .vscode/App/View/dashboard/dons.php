<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Gestion des dons</title>

<style>

body{
    font-family:'Segoe UI',sans-serif;
    background:#f6f8fb;
    margin:0;
    padding:40px;
}

.container{
    max-width:1200px;
    margin:auto;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

h1{
    color:#123847;
}

.btn{
    display:inline-block;
    padding:12px 20px;
    background:#123847;
    color:white;
    text-decoration:none;
    border-radius:10px;
    font-weight:bold;
    transition:.3s;
}

.btn:hover{
    background:#0d2c37;
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
    background:#123847;
    color:white;
}

th,
td{
    padding:16px;
    text-align:left;
}

tbody tr:nth-child(even){
    background:#f9fafb;
}

tbody tr:hover{
    background:#eef5f8;
}

.amount{
    font-weight:bold;
    color:#0f766e;
}

</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>💰 Liste des dons</h1>

        <a href="index.php?action=dashboard" class="btn">
            ← Retour au dashboard
        </a>
    </div>

    <div class="table-card">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Montant</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach($dons as $don): ?>

                <tr>
                    <td><?= $don['id'] ?></td>

                    <td><?= htmlspecialchars($don['email']) ?></td>

                    <td class="amount">
                        <?= number_format($don['montant'],2,',',' ') ?> €
                    </td>

                    <td><?= $don['date_don'] ?></td>
                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>

