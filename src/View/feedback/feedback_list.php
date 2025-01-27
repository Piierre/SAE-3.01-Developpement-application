<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des feedbacks</title>
    <link rel="stylesheet" href="/SAE-3.01-Developpement-application/web/assets/css/styles.css"> 
    <style>
        .fade-in {
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <header class="fade-in">
        <button class="back-button" onclick="window.location.href='/SAE-3.01-Developpement-application/web/frontController.php';">🏠 Accueil</button>
        <button class="toggle-dark-mode" onclick="toggleDarkMode()">🌙 Mode sombre</button>
    </header>
    <h1 class="fade-in">Liste des feedbacks</h1>
    <hr class="fade-in">

    <?php
    $feedbackController = new \App\Meteo\Controller\FeedbackController();
    $feedbacks = $feedbackController->listFeedbacks();

    if (empty($feedbacks)) {
        echo "<p class='fade-in'>Aucun feedback pour le moment.</p>";
    } else {
        foreach ($feedbacks as $feedback) {
            echo "<div class='fade-in' style='margin-bottom: 20px;'>";
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

            echo "</div><hr class='fade-in'>";
        }
    }
    ?>
    <script>
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
        }
    </script>
</body>
</html>
