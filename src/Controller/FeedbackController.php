<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Feedback;
use App\Entity\Vico;
use App\Exception\FormException;
use App\Form\FeedbackType;
use App\Form\RatingType;
use App\Repository\ClientRepository;
use App\Repository\FeedbackRepository;
use App\Repository\ProjectRepository;
use App\Repository\VicoRepository;
use App\Service\ApiService;
use App\Service\FeedbackService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/api/feedback')]
class FeedbackController extends AbstractFOSRestController
{
    private ApiService      $apiService;
    private FeedbackService $feedbackService;

    public function __construct(ApiService $apiService, FeedbackService $feedbackService)
    {
        $this->apiService = $apiService;
        $this->feedbackService = $feedbackService;
    }

    #[Route(path: '/{id}', methods: ['GET'])]
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

    #[Route(path: '/client/{clientId}/vico/{vicoId}/project/{projectId}', requirements: ['clientId' => '\d+', 'vicoId' => '\d+', 'projectId' => '\d+'], methods: ['POST'])]
    public function createFeedback(Request $request, ValidatorInterface $validator, int $clientId, int $vicoId, int $projectId, ClientRepository $clientRepository, VicoRepository $vicoRepository, ProjectRepository $projectRepository, FeedbackRepository $feedbackRepository)
    {
        $client = $clientRepository->find($clientId);
        if (empty($client)) {
            throw new NotFoundHttpException('Client not found.');
        }

        $vico = $vicoRepository->find($vicoId);
        if (empty($vico)) {
            throw new NotFoundHttpException('Vico not found.');
        }

        $project = $projectRepository->findOneBy(['id' => $projectId, 'vico' => $vico, 'creator' => $client]);
        if (empty($project)) {
            throw new NotFoundHttpException('Project not found.');
        }
        if (!empty($project->getFeedback())) {
            throw new BadRequestException('Project already have feedback.');
        }

        $payload = json_decode($request->getContent(), true);
        if (empty($payload)) {
            throw new BadRequestException('Empty payload.');
        }

        $feedback = new Feedback();
        $feedback
            ->setClient($client)
            ->setVico($vico)
            ->setProject($project);

        $form = $this->createForm(FeedbackType::class, $feedback)
            ->submit($payload);

        //        if (!$form->isValid()) {
//            return FormException::formError($form)->getFormError();
//        }

        $errors = $validator->validate($feedback);
        if (count($errors) > 0) {
            return $this->apiService->validationErrorResponse($errors);
        }

        $this->feedbackService->saveFeedback($feedback);

        return $feedback;
    }

    #[Route(path: '/{id}', methods: ['PUT'])]
    public function updateFeedback(Request $request, ValidatorInterface $validator, Feedback $feedback)
    {
        $payload = json_decode($request->getContent(), true);
        if (empty($payload)) {
            throw new BadRequestException('Empty payload.');
        }

        $form = $this->createForm(FeedbackType::class, $feedback)
            ->submit($payload, false);

//        if (!$form->isValid()) {
//            return FormException::formError($form)->getFormError();
//        }

        $errors = $validator->validate($feedback);
        if (count($errors) > 0) {
            return $this->apiService->validationErrorResponse($errors);
        }

        $this->feedbackService->saveFeedback($feedback);

        return $feedback;
    }

    #[Route(path: '/{id}', methods: ['DELETE'])]
    public function deleteFeedback(Feedback $feedback)
    {
        $this->feedbackService->removeFeedback($feedback);

        return ['success' => true];
    }

    #[Route(path: '/{id}/ratings', methods: ['POST'])]
    public function updateRatings(Request $request, ValidatorInterface $validator, Feedback $feedback)
    {
        $payload = json_decode($request->getContent(), true);
        if (empty($payload)) {
            throw new BadRequestException('Empty payload.');
        }

        $form = $this->createForm(RatingType::class, [])
            ->submit($payload, false);

        if (!$form->isValid()) {
            return ['errors' => FormException::formError($form)->getFormError()];
        }

        $this->feedbackService->addRatings($feedback, $payload['ratings']);

        return $feedback;
    }
}
