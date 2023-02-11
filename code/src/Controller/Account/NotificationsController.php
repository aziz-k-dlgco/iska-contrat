<?php

namespace App\Controller\Account;

use App\Repository\Account\NotificationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/api/notifications')]
class NotificationsController extends AbstractController
{
    #[Route('/', name: 'app_account_notifications_index', methods: ['GET'])]
    public function index(NotificationsRepository $repository): JsonResponse
    {
        $notifications = $repository->findBy(['user' => $this->getUser()]);
        return $this->json(array_map(fn($notification) => $notification->toArray(), $notifications));
    }
}
