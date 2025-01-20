<!DOCTYPE html>
<html lang="fr">
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
            xhr.open("GET", "search.php?query=" + encodeURIComponent(query), true);
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
                document.getElementById("results").innerHTML = "<p>Veuillez remplir tous les champs.</p>";
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("GET", "search.php?station_name=" + encodeURIComponent(stationName) + "&date=" + encodeURIComponent(date), true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById("results").innerHTML = this.responseText;
                }
            };
            xhr.send();
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 20px auto;
        }
        input, button {
            padding: 10px;
            margin: 5px;
            width: 90%;
        }
        button {
            cursor: pointer;
            background-color: #28a745;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #218838;
        }
        .suggestions div {
            padding: 10px;
            border: 1px solid #ddd;
            cursor: pointer;
        }
        .suggestions div:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h1>Recherche de Station Météo</h1>
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

    <div id="results" class="results"></div>
</body>
</html>
