<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - SAE 3.01</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, rgb(168, 172, 175), #00f2fe);
            color: #fff;
            text-align: center;
            padding: 50px;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        }

        .container {
            margin-top: 50px;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            font-size: 1.2rem;
            color: #fff;
            background: #28a745;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            background: #218838;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <h1>Bienvenue sur l'application météo SAE 3.01</h1>
    
    <div class="container">
        <a href="frontController.php?page=carte" class="btn">Voir la carte</a>
        <a href="frontController.php?page=recherche" class="btn">Faire une recherche</a>
    </div>

</body>
</html>
