// Fonction pour rechercher des stations en fonction de la requête
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

// Fonction pour sélectionner une station dans les suggestions
function selectStation(stationName) {
    document.getElementById("search").value = stationName;
    document.getElementById("suggestions").innerHTML = "";
}

// Fonction pour rechercher des mesures en fonction du nom de la station et de la date
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

// Fonction pour basculer en mode sombre
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