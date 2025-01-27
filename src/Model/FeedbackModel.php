<?php

namespace App\Meteo\Model;

use PDO;

class FeedbackModel
{
    private $db;

    public function __construct()
    {
        // Connexion à la base de données
        $this->db = new PDO('sqlite:' . ROOT . '/data/database.sqlite');
    }

    public function addFeedback($username, $message, $rating)
    {
        $stmt = $this->db->prepare('INSERT INTO feedback (username, message, rating, created_at) VALUES (:username, :message, :rating, :created_at)');
        $stmt->execute([
            ':username' => $username,
            ':message' => $message,
            ':rating' => $rating,
            ':created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getAllFeedbacks()
    {
        $stmt = $this->db->query('SELECT * FROM feedback ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
