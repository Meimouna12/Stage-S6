<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - All Inclusive</title>

    <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:'Space Grotesk',sans-serif;
            min-height:100vh;

            display:flex;
            justify-content:center;
            align-items:center;

            background:
            linear-gradient(
                135deg,
                #113847,
                #2e6674
            );
        }

        .login-card{
            width:420px;

            background:white;

            padding:40px;

            border-radius:20px;

            box-shadow:
            0 20px 50px rgba(0,0,0,.2);
        }

        .logo{
            text-align:center;
            margin-bottom:25px;
        }

        .logo img{
            width:80px;
            height:80px;
            border-radius:50%;
        }

        h1{
            text-align:center;

            font-family:'Fraunces',serif;

            color:#113847;

            margin-bottom:10px;
        }

        .subtitle{
            text-align:center;
            color:#777;
            margin-bottom:30px;
        }

        .form-group{
            margin-bottom:20px;
        }

        label{
            display:block;
            margin-bottom:8px;
            font-weight:600;
        }

        input{
            width:100%;

            padding:14px;

            border:1px solid #ddd;

            border-radius:10px;

            font-size:15px;
        }

        input:focus{
            outline:none;
            border-color:#113847;
        }

        .btn{
            width:100%;

            border:none;

            background:#e66a3a;

            color:white;

            padding:15px;

            border-radius:10px;

            font-size:16px;

            font-weight:700;

            cursor:pointer;

            transition:.3s;
        }

        .btn:hover{
            transform:translateY(-2px);
        }

        .error{
            background:#ffe4e4;

            color:#c0392b;

            padding:12px;

            border-radius:10px;

            margin-bottom:20px;
        }

        .back{
            text-align:center;
            margin-top:20px;
        }

        .back a{
            color:#113847;
            text-decoration:none;
            font-weight:600;
        }

        .back a:hover{
            text-decoration:underline;
        }
    </style>
</head>

<body>

<div class="login-card">

    <div class="logo">
        <img src="assets/logo/logo du site.jpg" alt="Logo">
    </div>

    <h1>Connexion</h1>

    <p class="subtitle">
        Espace adhérent All Inclusive
    </p>

    <?php if (!empty($error)): ?>
        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label>Email</label>

            <input
                type="email"
                name="email"
                required
            >
        </div>

        <div class="form-group">
            <label>Mot de passe</label>

            <input
                type="password"
                name="password"
                required
            >
        </div>

        <button class="btn" type="submit">
            Se connecter
        </button>

    </form>

    <div class="back">
        <a href="index.php">
            ← Retour au site
        </a>
    </div>

</div>

</body>
</html>