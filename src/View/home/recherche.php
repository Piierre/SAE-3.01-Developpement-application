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

        .back-button {
    width: 120px;  /* Définir une largeur fixe pour réduire la taille du bouton */
    padding: 10px 0;  /* Ajustement du padding pour éviter un bouton trop grand */
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
}



        .back-button:hover {
            background-color: #218838;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            h1 {
                font-size: 1.8rem;
            }

            input, button {
                font-size: 0.9rem;
                padding: 10px;
            }

            th, td {
                font-size: 0.9rem;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
<button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/index.php';">🏠 Accueil</button>
    
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
                    <!-- Les données seront insérées ici -->
                </tbody>
            </table>
        </div>
    </div>

    <footer>
    SAE - Projet 3.01 - Développement d'application
    </footer>
</body>
</html>
