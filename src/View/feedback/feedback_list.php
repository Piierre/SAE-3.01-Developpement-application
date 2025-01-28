<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des feedbacks</title>
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css"> <!-- Lien vers le CSS -->
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/auth.css"> <!-- Lien vers le CSS -->
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
        }

        body {
            background: linear-gradient(to right, rgb(168, 172, 175), #00f2fe);
            color: #fff;
            padding: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            overflow-y: auto; /* Assurer que le corps est défilable */
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .back-button, .toggle-dark-mode {
            padding: 10px 20px;
            font-size: 1rem;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .back-button:hover, .toggle-dark-mode:hover {
            background-color: #218838;
        }

        @media (max-width: 768px) {
            .back-button, .toggle-dark-mode {
                font-size: 0.8rem;
                padding: 8px 16px;
            }
        }

        @media (max-width: 480px) {
            .back-button, .toggle-dark-mode {
                font-size: 0.7rem;
                padding: 6px 12px;
            }
        }

        .fade-in {
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .feedback {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .feedback-content {
            flex: 1;
        }

        .feedback button {
            padding: 20px 40px;
            font-size: 1.5rem;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .feedback button:hover {
            background-color: #218838;
        }

        footer {
            margin-top: 40px;
            font-size: 0.9rem;
        }

        /* Styles pour le mode sombre */
        body.dark-mode {
            background: linear-gradient(to right, #2c3e50, #4ca1af);
            color: #ddd;
        }

        .dark-mode h1, .dark-mode footer {
            color: #ddd;
        }

        .dark-mode .back-button, .dark-mode .toggle-dark-mode, .dark-mode .feedback button {
            background-color: #444;
        }

        .dark-mode .back-button:hover, .dark-mode .toggle-dark-mode:hover, .dark-mode .feedback button:hover {
            background-color: #333;
        }

        .dark-mode .feedback {
            background: rgba(255, 255, 255, 0.1);
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    <header>
        <h1>Liste des feedbacks</h1>
        <div class="button-home">
            <button class="btn" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php'">🏠 Accueil</button> <!-- Bouton pour retourner à l'accueil -->
        </div>
        <div class="button-container">
            <button class="btn" id="darkModeButton" onclick="toggleDarkMode()">🌙 Mode sombre</button> <!-- Bouton pour basculer le mode sombre -->
        </div>
    </header>
    <main>
        <?php
        $feedbackController = new \App\Meteo\Controller\FeedbackController();
        $feedbacks = $feedbackController->listFeedbacks(); // Récupérer la liste des feedbacks

        if (empty($feedbacks)) {
            echo "<p class='fade-in'>Aucun feedback pour le moment.</p>"; // Message si aucun feedback n'est trouvé
        } else {
            foreach ($feedbacks as $feedback) {
                echo "<div class='feedback fade-in'>";
                echo "<div class='feedback-content'>";
                echo "<strong>Utilisateur :</strong> " . htmlspecialchars($feedback['name']) . "<br>"; // Afficher le nom de l'utilisateur
                echo "<strong>Message :</strong> " . htmlspecialchars($feedback['message']) . "<br>"; // Afficher le message
                echo "<strong>Note :</strong> " . htmlspecialchars($feedback['rating']) . "/5<br>"; // Afficher la note
                echo "<em>Envoyé le : " . htmlspecialchars($feedback['created_at']) . "</em><br>"; // Afficher la date d'envoi
                echo "<strong>Status :</strong> " . htmlspecialchars($feedback['status']) . "<br>"; // Afficher le statut
                echo "</div>";

                if ($feedback['status'] === 'pending') {
                    echo "<form method='post' action='/SAE-3.01-Developpement-application/web/frontController.php?action=approveFeedback'>";
                    echo "<input type='hidden' name='feedback_id' value='" . htmlspecialchars($feedback['id']) . "'>";
                    echo "<button type='submit'>Approuver</button>"; // Bouton pour approuver le feedback
                    echo "</form>";
                } elseif ($feedback['status'] === 'approved') {
                    echo "<form method='post' action='/SAE-3.01-Developpement-application/web/frontController.php?action=disapproveFeedback'>";
                    echo "<input type='hidden' name='feedback_id' value='" . htmlspecialchars($feedback['id']) . "'>";
                    echo "<button type='submit'>Désapprouver</button>"; // Bouton pour désapprouver le feedback
                    echo "</form>";
                }

                echo "</div>";
            }
        }
        ?>
    </main>
    <footer class="fade-in">
        SAE - Projet 3.01 - Développement d'application 
    </footer>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode'); // Basculer le mode sombre
            document.documentElement.classList.toggle('dark-mode');
            const darkModeButton = document.getElementById('darkModeButton');
            if (document.body.classList.contains('dark-mode')) {
                darkModeButton.innerHTML = '☀️ Mode clair'; // Changer le texte du bouton en fonction du mode
            } else {
                darkModeButton.innerHTML = '🌙 Mode sombre';
            }
        }
    </script>
</body>
</html>
