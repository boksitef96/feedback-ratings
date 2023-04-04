<?php

namespace App\Service;

use App\Entity\Feedback;
use App\Entity\Rating;
use App\Repository\FeedbackRepository;
use App\Repository\RatingRepository;
use App\Repository\RatingTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FeedbackService
{
    private FeedbackRepository     $feedbackRepository;
    private EntityManagerInterface $entityManager;
    private RatingRepository       $ratingRepository;
    private RatingTypeRepository   $ratingTypeRepository;

    public function __construct(FeedbackRepository $feedbackRepository, EntityManagerInterface $entityManager, RatingRepository $ratingRepository, RatingTypeRepository $ratingTypeRepository)
    {
        $this->feedbackRepository = $feedbackRepository;
        $this->entityManager = $entityManager;
        $this->ratingRepository = $ratingRepository;
        $this->ratingTypeRepository = $ratingTypeRepository;
    }

    public function saveFeedback(Feedback $feedback)
    {
        $project = $feedback->getProject();
        $project->setFeedback($feedback);

        $this->feedbackRepository->save($feedback, true);
    }

    public function removeFeedback(Feedback $feedback)
    {
        $feedback->removeAllRating();
        $project = $feedback->getProject();
        $project->setFeedback(null);

        $this->feedbackRepository->remove($feedback, true);

        $this->entityManager->flush();
    }

    public function addRatings(Feedback $feedback, array $ratings)
    {
        foreach ($ratings as $ratingData) {
            $ratingType = $this->ratingTypeRepository->find($ratingData['type']);
            if (empty($ratingType)) {
                throw new NotFoundHttpException('Rating type not found.');
            }

            $rating = $this->ratingRepository->findOneBy(['feedback' => $feedback, 'ratingType' => $ratingType]);
            if (empty($rating)) {
                $rating = new Rating();
                $rating
                    ->setFeedback($feedback)
                    ->setRatingType($ratingType);
            }

            $rating->setValue($ratingData['value']);
            $feedback->addRating($rating);

            $this->ratingRepository->save($rating);
        }

        $this->entityManager->flush();

        return $feedback;
    }
}