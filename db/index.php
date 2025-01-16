<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Station</title>
    <script>
        function searchStation(query) {
            if (query.length === 0) {
                document.getElementById("results").innerHTML = "";
                return;
            }
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "search.php?query=" + encodeURIComponent(query), true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById("results").innerHTML = this.responseText;
                }
            };
            xhr.send();
        }

        function showMeasures(stationId) {
            const order = document.getElementById("order").value;
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "search.php?station_id=" + encodeURIComponent(stationId) + "&order=" + encodeURIComponent(order), true);
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById("measures").innerHTML = this.responseText;
                }
            };
            xhr.send();
        }

        function updateMeasures() {
            const firstResult = document.getElementById('results').querySelector('div');
            if (firstResult) {
                const stationId = firstResult.getAttribute('onclick').match(/'([^']+)'/)[1];
                showMeasures(stationId);
            }
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .results, .measures {
            margin-top: 20px;
        }
        div {
            cursor: pointer;
            padding: 5px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
        }
        div:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h1>Recherche de Station</h1>
    <input 
        type="text" 
        id="search" 
        placeholder="Rechercher une station par nom..." 
        onkeyup="searchStation(this.value)"
    >
    <select id="order" onchange="updateMeasures()">
        <option value="DESC">Date Descendante</option>
        <option value="ASC">Date Ascendante</option>
    </select>
    <div id="results" class="results"></div>
    <div id="measures" class="measures"></div>
</body>
</html>
