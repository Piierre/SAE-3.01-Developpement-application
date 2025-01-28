<?php

namespace App\Meteo\Model;

require_once __DIR__ . '/../config/Conf.php';

use PDO;
use App\Meteo\Config\Conf;

class FeedbackModel
{
    // Ajoute un feedback dans la base de données
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

    // Récupère tous les feedbacks de la base de données
    public function getAllFeedbacks()
    {
        $pdo = Conf::getPDO();
        $stmt = $pdo->query('SELECT * FROM Feedback ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Approuve un feedback
    public function approveFeedback($feedbackId)
    {
        $pdo = Conf::getPDO();
        $stmt = $pdo->prepare('UPDATE Feedback SET status = :status WHERE id = :id');
        $stmt->execute([
            ':status' => 'approved',
            ':id' => $feedbackId
        ]);
    }

    // Désapprouve un feedback
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