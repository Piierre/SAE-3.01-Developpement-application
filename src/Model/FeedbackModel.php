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
        $stmt = $pdo->prepare('INSERT INTO Feedback (name, message, rating, created_at, status) VALUES (:name, :message, :rating, :created_at, :status)');
        $stmt->execute([
            ':name' => $name,
            ':message' => $message,
            ':rating' => $rating,
            ':created_at' => date('Y-m-d H:i:s'),
            ':status' => 'pending'
        ]);
    }

    public function getAllFeedbacks()
    {
        $pdo = Conf::getPDO();
        $stmt = $pdo->query('SELECT * FROM Feedback ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveFeedback($feedbackId)
    {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare('UPDATE Feedback SET status = :status WHERE id = :id');
        $stmt->execute([
            ':status' => 'approved',
            ':id' => $feedbackId
        ]);
    }

    public function disapproveFeedback($feedbackId)
    {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare('UPDATE Feedback SET status = :status WHERE id = :id');
        $stmt->execute([
            ':status' => 'pending',
            ':id' => $feedbackId
        ]);
    }
}