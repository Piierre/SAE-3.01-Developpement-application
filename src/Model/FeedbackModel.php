<?php

namespace App\Meteo\Model;

require_once __DIR__ . '/../config/Conf.php';

use PDO;

class FeedbackModel
{
    public function addFeedback($username, $message, $rating)
    {
        global $db;
        $stmt = $db->prepare('INSERT INTO Feedback (username, message, rating, created_at) VALUES (:username, :message, :rating, :created_at)');
        $stmt->execute([
            ':username' => $username,
            ':message' => $message,
            ':rating' => $rating,
            ':created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getAllFeedbacks()
    {
        global $db;
        $stmt = $db->query('SELECT * FROM Feedback ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}