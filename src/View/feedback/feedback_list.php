<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des feedbacks</title>
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
        }

        .feedback strong {
            display: block;
            margin-bottom: 5px;
        }

        .feedback em {
            display: block;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .feedback button {
            padding: 10px 20px;
            font-size: 1rem;
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

        /* Dark mode styles */
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
    <header class="header-container fade-in">
        <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php';">🏠 Accueil</button>
        <button class="toggle-dark-mode" onclick="toggleDarkMode()">🌙 Mode sombre</button>
    </header>
    <h1 class="fade-in">Liste des feedbacks</h1>

    <?php
    $feedbackController = new \App\Meteo\Controller\FeedbackController();
    $feedbacks = $feedbackController->listFeedbacks();

    if (empty($feedbacks)) {
        echo "<p class='fade-in'>Aucun feedback pour le moment.</p>";
    } else {
        foreach ($feedbacks as $feedback) {
            echo "<div class='feedback fade-in'>";
            echo "<strong>Utilisateur :</strong> " . htmlspecialchars($feedback['name']) . "<br>";
            echo "<strong>Message :</strong> " . htmlspecialchars($feedback['message']) . "<br>";
            echo "<strong>Note :</strong> " . htmlspecialchars($feedback['rating']) . "/5<br>";
            echo "<em>Envoyé le : " . htmlspecialchars($feedback['created_at']) . "</em><br>";
            echo "<strong>Status :</strong> " . htmlspecialchars($feedback['status']) . "<br>";

            if ($feedback['status'] === 'pending') {
                echo "<form method='post' action='/SAE-3.01-Developpement-application/web/frontController.php?action=approveFeedback'>";
                echo "<input type='hidden' name='feedback_id' value='" . htmlspecialchars($feedback['id']) . "'>";
                echo "<button type='submit'>Approuver</button>";
                echo "</form>";
            } elseif ($feedback['status'] === 'approved') {
                echo "<form method='post' action='/SAE-3.01-Developpement-application/web/frontController.php?action=disapproveFeedback'>";
                echo "<input type='hidden' name='feedback_id' value='" . htmlspecialchars($feedback['id']) . "'>";
                echo "<button type='submit'>Désapprouver</button>";
                echo "</form>";
            }

            echo "</div>";
        }
    }
    ?>
    <footer class="fade-in">
        SAE - Projet 3.01 - Développement d'application 
    </footer>
    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
    </script>
</body>
</html>
