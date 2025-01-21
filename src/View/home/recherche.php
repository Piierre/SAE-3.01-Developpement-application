<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Station Météo</title>
    <script>
        function searchStations(query) {
            if (query.length === 0) {
                document.getElementById("suggestions").innerHTML = "";
                return;
            }
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "/SAE-3.01-Developpement-application/src/View/map/search.php?query=" + encodeURIComponent(query), true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById("suggestions").innerHTML = this.responseText;
                }
            };
            xhr.send();
        }

        function selectStation(stationName) {
            document.getElementById("search").value = stationName;
            document.getElementById("suggestions").innerHTML = "";
        }

        function searchMeasures() {
            const stationName = document.getElementById("search").value.trim();
            const date = document.getElementById("date").value.trim();

            if (stationName === "" || date === "") {
                document.getElementById("results").innerHTML = "<p class='error-msg'>Veuillez remplir tous les champs.</p>";
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("GET", "/SAE-3.01-Developpement-application/src/View/map/search.php?station_name=" + encodeURIComponent(stationName) + "&date=" + encodeURIComponent(date), true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById("results").innerHTML = this.responseText;
                } else {
                    document.getElementById("results").innerHTML = "<p class='error-msg'>Erreur lors de la récupération des données.</p>";
                }
            };
            xhr.send();
        }

        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            document.querySelectorAll('.btn').forEach(button => {
                button.classList.toggle('dark-mode-btn');
            });

            const darkModeButton = document.getElementById('darkModeButton');
            if (document.body.classList.contains('dark-mode')) {
                darkModeButton.innerHTML = "☀️ Mode clair";
            } else {
                darkModeButton.innerHTML = "🌙 Mode sombre";
            }
        }
    </script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            background: linear-gradient(to right, rgb(168, 172, 175), #00f2fe);
            color: #fff;
            padding: 40px 20px;
            text-align: center;
        }

        .btn {
    width: 120px;
    padding: 10px 0;
    font-size: 1rem;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    position: absolute;
    top: 20px;
    right: 20px;
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px; /* Espacement entre l'emoji et le texte */
}

.btn:hover {
    background-color: #218838;
}


        .dark-mode-btn {
            background-color: #444 !important;
            color: #ddd !important;
        }

        h1 {
            font-size: 2.2rem;
            font-weight: bold;
            margin-bottom: 30px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        }

        input, button {
            padding: 15px;
            margin: 10px 0;
            width: 100%;
            max-width: 500px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            outline: none;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        input {
            color: #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        button {
            cursor: pointer;
            background-color: #28a745;
            color: white;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        button:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .suggestions {
            max-width: 500px;
            margin: 10px auto;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .suggestions div {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            cursor: pointer;
            color: #fff;
        }

        .suggestions div:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .results {
            margin-top: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 12px;
            text-align: center;
            font-size: 1rem;
        }

        th {
            background: rgba(0, 0, 0, 0.3);
            font-weight: bold;
            text-transform: uppercase;
        }

        td {
            background: rgba(255, 255, 255, 0.1);
        }

        .error-msg {
            color: #ff5e57;
            font-weight: bold;
            margin-top: 15px;
        }

        footer {
            margin-top: 40px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* Dark mode styles */
        body.dark-mode {
            background: linear-gradient(to right, #2c3e50, #4ca1af);
            color: #ddd;
        }

        body.dark-mode h1, body.dark-mode footer {
            color: #ddd;
        }

        body.dark-mode .container {
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <button class="btn" onclick="window.location.href='/SAE-3.01-Developpement-application/src/View/home/index.php'">🏠 Accueil</button>
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
