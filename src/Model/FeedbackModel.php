<?php

namespace App\Meteo\Model;

require_once __DIR__ . '/../config/Conf.php';

use PDO;
use App\Meteo\Config\Conf;

class FeedbackModel
{
    public function addFeedback($name, $message, $rating)
    {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare('INSERT INTO Feedback (name, message, rating, created_at) VALUES (:name, :message, :rating, :created_at)');
        $stmt->execute([
            ':name' => $name,
            ':message' => $message,
            ':rating' => $rating,
            ':created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getAllFeedbacks()
    {
        $pdo = Conf::getPDO();
        $stmt = $pdo->query('SELECT * FROM Feedback ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}