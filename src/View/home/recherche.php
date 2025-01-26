<?php
$stationName = isset($_GET['station_name']) ? $_GET['station_name'] : '';
$searchDate = isset($_GET['date']) ? $_GET['date'] : '';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/recherche.css">
</head>
    <title>Recherche de Station Météo</title>
    
    <script src="/SAE-3.01-Developpement-application/web/assets/css/js/recherche.js"></script>
    <script>
    document.querySelector("form").addEventListener("submit", function(event) {
        let stationName = document.getElementById('search').value.trim();
        let searchDate = document.getElementById('date').value.trim();
        
        if (!stationName || !searchDate) {
            alert("Veuillez remplir tous les champs avant d'ajouter à la Météothèque.");
            event.preventDefault();
        }
    });
</script>


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
            value="<?= htmlspecialchars($stationName) ?>"
            onkeyup="searchStations(this.value)"
        >
        <div id="suggestions" class="suggestions"></div>

        <input 
            type="date" 
            id="date"   
            placeholder="Sélectionner une date"
            value="<?= htmlspecialchars($searchDate) ?>"
        >
        <button onclick="searchMeasures()">Rechercher</button>

        <form action="/SAE-3.01-Developpement-application/web/frontController.php?action=addMeteotheque" method="post" onsubmit="return validateForm()">
    <input type="hidden" name="titre" id="hiddenTitre">
    <input type="hidden" name="description" id="hiddenDescription">
    <input type="hidden" name="station_name" id="hiddenStation">
    <input type="hidden" name="search_date" id="hiddenDate">

    <button type="submit">Ajouter cette recherche à ma Météothèque</button>
</form>

<script>
function validateForm() {
    let stationName = document.getElementById('search').value.trim();
    let searchDate = document.getElementById('date').value.trim();

    if (stationName === '' || searchDate === '') {
        alert("Veuillez remplir tous les champs avant d'ajouter à la Météothèque.");
        return false;
    }

    // Mettre à jour les valeurs des champs cachés avant l'envoi
    document.getElementById('hiddenTitre').value = `Recherche: ${stationName}`;
    document.getElementById('hiddenDescription').value = `Recherche pour la station ${stationName} à la date ${searchDate}`;
    document.getElementById('hiddenStation').value = stationName;
    document.getElementById('hiddenDate').value = searchDate;

    return true;
}
</script>


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
