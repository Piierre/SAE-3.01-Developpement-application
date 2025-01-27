<?php

namespace App\Meteo\Controller;

use App\Meteo\Model\FeedbackModel;

class FeedbackController
{
    public function submitFeedback($username, $message, $rating)
    {
        $feedbackModel = new FeedbackModel();
        $feedbackModel->addFeedback($username, $message, $rating);
    }

    public function listFeedbacks()
    {
        $feedbackModel = new FeedbackModel();
        return $feedbackModel->getAllFeedbacks();
    }
}
