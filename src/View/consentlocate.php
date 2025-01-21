<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Géolocalisation et RGPD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Style de la bannière RGPD */
        #consent-banner {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f9f9f9;
            border-top: 1px solid #ccc;
            padding: 15px;
            text-align: center;
            z-index: 9999;
        }

        #consent-banner button {
            margin-left: 10px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #consent-banner button:hover {
            background-color: #0056b3;
        }

        /* Contenu principal */
        #content {
            display: none;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Bannière RGPD -->
    <div id="consent-banner">
        <p>
            Ce site souhaite accéder à votre géolocalisation pour personnaliser votre expérience. 
            <br>Consultez notre <a href="charte.html" target="_blank">charte de confidentialité</a>.
        </p>
        <button id="accept-consent">Accepter</button>
        <button id="decline-consent">Refuser</button>
    </div>

    <!-- Contenu principal -->
    <div id="content">
        <h1>Bienvenue sur notre site !</h1>
        <p id="location-info">Nous récupérons votre position...</p>
    </div>

    <script>
        // Vérifier si le consentement a déjà été donné
        document.addEventListener("DOMContentLoaded", function () {
            const consent = localStorage.getItem("geo_consent");

            if (consent === "accepted") {
                // Lancer la géolocalisation
                enableGeolocation();
            } else if (consent === "declined") {
                // L'utilisateur a refusé
                document.getElementById("consent-banner").style.display = "none";
                document.getElementById("content").style.display = "block";
                document.getElementById("location-info").textContent =
                    "Vous avez refusé la géolocalisation.";
            }
        });

        // Actions des boutons
        document.getElementById("accept-consent").addEventListener("click", function () {
            localStorage.setItem("geo_consent", "accepted");
            enableGeolocation();
        });

        document.getElementById("decline-consent").addEventListener("click", function () {
            localStorage.setItem("geo_consent", "declined");
            document.getElementById("consent-banner").style.display = "none";
            document.getElementById("content").style.display = "block";
            document.getElementById("location-info").textContent =
                "Vous avez refusé la géolocalisation.";
        });

        // Fonction pour activer la géolocalisation
        function enableGeolocation() {
            document.getElementById("consent-banner").style.display = "none";
            document.getElementById("content").style.display = "block";

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;
                        document.getElementById("location-info").textContent =
                            `Votre position : Latitude ${lat}, Longitude ${lon}`;
                    },
                    function () {
                        document.getElementById("location-info").textContent =
                            "Impossible de récupérer votre position.";
                    }
                );
            } else {
                document.getElementById("location-info").textContent =
                    "La géolocalisation n'est pas supportée par votre navigateur.";
            }
        }
    </script>
</body>
</html>
