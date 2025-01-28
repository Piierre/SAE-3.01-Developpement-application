<?php

namespace App\Meteo\Controller;

// Inclusion du modèle pour la gestion des retours utilisateurs
require_once __DIR__ . '/../Model/FeedbackModel.php';

use App\Meteo\Model\FeedbackModel;

// Contrôleur pour gérer les retours/commentaires des utilisateurs
class FeedbackController
{
    // Méthode pour soumettre un nouveau retour utilisateur
    public function submitFeedback($username, $message, $rating)
    {
        $feedbackModel = new FeedbackModel();
        $feedbackModel->addFeedback($username, $message, $rating);
    }

    // Méthode pour récupérer la liste de tous les retours
    public function listFeedbacks()
    {
        $feedbackModel = new FeedbackModel();
        return $feedbackModel->getAllFeedbacks();
    }

    // Méthode pour approuver un retour utilisateur
    public function approveFeedback($feedbackId)
    {
        $feedbackModel = new FeedbackModel();
        $feedbackModel->approveFeedback($feedbackId);
    }

    // Méthode pour désapprouver un retour utilisateur
    public function disapproveFeedback($feedbackId)
    {
        $feedbackModel = new FeedbackModel();
        $feedbackModel->disapproveFeedback($feedbackId);
    }
}
