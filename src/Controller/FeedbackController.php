<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Feedback;
use App\Entity\Vico;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/feedback')]
class FeedbackController
{
    #[Route(path: '/{id}')]
    public function getById(Feedback $feedback)
    {
        return $feedback;
    }

    #[Route(path: '/vico/{id}')]
    public function listForVico(Vico $vico)
    {
        return $vico->getFeedbacks();
    }

    #[Route(path: '/client/{id}')]
    public function listForClient(Client $client)
    {
        return $client->getFeedbacks();
    }

    public function createFeedback()
    {

    }

    public function updateFeedback()
    {

    }

    public function deleteFeedback()
    {

    }

    public function addRating()
    {

    }

    public function updateRating()
    {

    }

    public function deleteRating()
    {

    }
}
