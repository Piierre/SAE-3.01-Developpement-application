<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des feedbacks</title>
</head>
<body>
    <h1>Liste des feedbacks</h1>
    <a href="/SAE-3.01-Developpement-application/web/frontController.php?page=feedback">Donner un feedback</a>
    <hr>

    <?php
    $feedbackController = new \App\Meteo\Controller\FeedbackController();
    $feedbacks = $feedbackController->listFeedbacks();

    if (empty($feedbacks)) {
        echo "<p>Aucun feedback pour le moment.</p>";
    } else {
        foreach ($feedbacks as $feedback) {
            echo "<div style='margin-bottom: 20px;'>";
            echo "<strong>Utilisateur :</strong> " . htmlspecialchars($feedback['username']) . "<br>";
            echo "<strong>Message :</strong> " . htmlspecialchars($feedback['message']) . "<br>";
            echo "<strong>Note :</strong> " . htmlspecialchars($feedback['rating']) . "/5<br>";
            echo "<em>Envoyé le : " . htmlspecialchars($feedback['created_at']) . "</em>";
            echo "</div><hr>";
        }
    }
    ?>
</body>
</html>
