<?php

namespace App\Controller\Account;

use App\Entity\Account\User;
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
        $notifications = $repository->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);
        return $this->json(array_map(fn($notification) => $notification->toArray(), $notifications));
    }

    // Fetch notification on the last year use createAt
    #[Route('/calendar', name: 'app_account_notifications_calendar', methods: ['GET'])]
    public function calendar(NotificationsRepository $repository): JsonResponse
    {
        /* @var User $user */
        $user = $this->getUser();
        // Object of class DateTime could not be converted to string - '$lte' => new \DateTime()
        $notifications = $repository->findUserLastYearNotifications($user);
        return $this->json(array_map(function ($notification) {
            return [
                'id' => $notification->getId(),
                'title' => $notification->getTitle(),
                'description' => $notification->getText(),
                'color' => $notification->getColor(),
                // Format objectId to display UUID first 5 and last 5 characters
                'objectId' => substr($notification->getObjectId(), 0, 5) . '...' . substr($notification->getObjectId(), -5),
                'link' => $notification->getLink(),
                'createdAt' => $notification->getCreatedAt()->format('F d, Y'),
                'timestamp' => $notification->getCreatedAt()->setTime(0, 0, 0)->getTimestamp(),
            ];
        }, $notifications));
    }
}
