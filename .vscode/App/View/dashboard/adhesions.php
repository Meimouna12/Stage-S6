
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Liste des adhésions</title>

<style>

body{
    font-family:'Segoe UI',sans-serif;
    background:#f6f8fb;
    margin:0;
    padding:40px;
}

.container{
    max-width:1400px;
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
    margin:0;
}

.btn{
    display:inline-block;
    padding:12px 20px;
    background:#123847;
    color:white;
    text-decoration:none;
    border-radius:10px;
    font-weight:600;
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

th{
    padding:16px;
    text-align:left;
}

td{
    padding:16px;
    border-bottom:1px solid #e5e7eb;
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

.empty{
    text-align:center;
    padding:30px;
    color:#6b7280;
}

</style>
</head>

<body>

<div class="container">

    <div class="header">

        <h1>📋 Liste des adhésions</h1>

        <a href="index.php?action=dashboard" class="btn">
            ← Retour au dashboard
        </a>

    </div>

    <div class="table-card">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Antenne</th>
                    <th>Montant</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>

            <?php if(empty($adhesions)): ?>

                <tr>
                    <td colspan="7" class="empty">
                        Aucune adhésion enregistrée.
                    </td>
                </tr>

            <?php else: ?>

                <?php foreach($adhesions as $adhesion): ?>

                    <tr>
                        <td><?= $adhesion['id'] ?></td>

                        <td><?= htmlspecialchars($adhesion['prenom']) ?></td>

                        <td><?= htmlspecialchars($adhesion['nom']) ?></td>

                        <td><?= htmlspecialchars($adhesion['email']) ?></td>

                        <td><?= htmlspecialchars($adhesion['antenne']) ?></td>

                        <td class="amount">
                            <?= number_format($adhesion['montant'],2,',',' ') ?> €
                        </td>

                        <td><?= htmlspecialchars($adhesion['date_adhesion']) ?></td>
                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>

