<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/recherche.css">
</head>
    <title>Recherche de Station Météo</title>
    
    <script src="/SAE-3.01-Developpement-application/web/assets/css/js/recherche.js"></script>

</head>
<body>
    <button class="btn" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php'">🏠 Accueil</button>
    <button class="btn" id="darkModeButton" style="right: 160px;" onclick="toggleDarkMode()">🌙 Mode sombre</button>

    <h1>Recherche de Station Météo</h1>
    <div class="container">
        <input 
            type="text" 
            id="search" 
            placeholder="Rechercher une station..." 
            onkeyup="searchStations(this.value)"
        >
        <div id="suggestions" class="suggestions"></div>

        <input 
            type="date" 
            id="date"   
            placeholder="Sélectionner une date"
        >
        <button onclick="searchMeasures()">Rechercher</button>

        <form action="/SAE-3.01-Developpement-application/web/frontController.php?action=addMeteotheque" method="post">
            <input type="hidden" name="titre" value="Titre par défaut">
            <input type="hidden" name="description" value="Description par défaut">
            <button type="submit">Ajouter cette recherche à ma Météothèque</button>
        </form>

        <div id="results" class="results">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Température (°C)</th>
                        <th>Humidité (%)</th>
                        <th>Vent (m/s)</th>
                        <th>Précipitations (mm)</th>
                    </tr>
                </thead>
                <tbody id="dataTable">
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        SAE - Projet 3.01 - Développement d'application
    </footer>
</body>
</html>
