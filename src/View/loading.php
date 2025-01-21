<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exemple de Loader avec JavaScript</title>
    <style>
        /* Style du loader */
        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #f3f3f3;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Animation du spinner */
        .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Contenu caché initialement */
        #content {
            display: none;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div id="loader">
        <div class="spinner"></div>
    </div>

    <!-- Contenu principal du site -->
    <div id="content">
        <h1>Bienvenue sur mon site</h1>
        <p>Ce contenu s'affiche après le chargement simulé avec JavaScript.</p>
    </div>

    <script>
        // Simuler un chargement
        window.addEventListener("load", function () {
            // Temps de chargement simulé
            setTimeout(function () {
                // Masquer le loader
                document.getElementById("loader").style.display = "none";
                // Afficher le contenu
                document.getElementById("content").style.display = "block";
            }, 3000); // 3 secondes de délai
        });
    </script>
</body>
</html>
