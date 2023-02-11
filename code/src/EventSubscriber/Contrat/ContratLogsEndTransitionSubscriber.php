<?php

namespace App\EventSubscriber\Contrat;

use App\Entity\Account\Notifications;
use App\Entity\Account\User;
use App\Entity\Contrat\Contrat;
use App\Entity\Contrat\ContratLogs;
use App\Entity\Contrat\ContratValidation;
use App\Repository\Account\UserRepository;
use Carbon\CarbonInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\Event\CompletedEvent;
use Symfony\Component\Workflow\WorkflowInterface;

class ContratLogsEndTransitionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private WorkflowInterface $contractRequestStateMachine,
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository
    )
    {
    }

    public function guardReview(CompletedEvent $event)
    {

        /** @var Contrat $contrat */
        $contrat = $event->getSubject();

        // On récupère l'utilisateur connecté par défaut
        /** @var User $user */
        $user = $this->security->getUser();
        $transitionName = $event->getTransition()->getName();
        // Si la transition est reassign_to_agent, on récupère l'utilisateur qui a été assigné
        if($transitionName === 'reassign_to_agent') {
            /** @var User $user */
            $user = $event->getContext()['user'];
            // On met à jour le délai de traitement
            $contrat->setValidation(
                (new ContratValidation())
                    ->setUser($user)
                    ->setIsHorsDelai(false)
                    ->setDelai(
                        $user
                            ->getAdditionnalData()
                            ->getDelaiTraitementContrat() ?? (CarbonInterval::days(14)->toDateInterval())
                    )
            );
        }
        elseif ($transitionName === 'approve_legal_department'){
            // On met à jour le délai de traitement
            $contrat->setValidation(
                (new ContratValidation())
                    ->setUser($user)
                    ->setIsHorsDelai(false)
                    ->setDelai(
                        $user
                            ->getAdditionnalData()
                            ->getDelaiTraitementContrat() ?? (CarbonInterval::days(14)->toDateInterval())
                    )
                ->setIsManagerAction(true)
            );
        }
        // Si la transition est reject_agent ou approve_agent, on marque la validation comme faite
        elseif ($transitionName === 'reject_agent' || $transitionName === 'approve_agent'){
            $validation = $contrat->getValidation();
            $validation->setIsHorsDelai(
                // add delai to created_at, if it's less than now, it's hors delai
                $validation->getCreatedAt()->add($validation->getDelai()) < new \DateTime()
            )
            ->setUpdatedAt(new \DateTimeImmutable());
            $contrat->setValidation($validation);
        }
        // Récupération des métadonnées de la transition
        $metadata = $this->contractRequestStateMachine->getMetadataStore()->getTransitionMetadata(
            $event->getTransition()
        );
        $title = $metadata['title'] ?? 'Changement de statut';
        $text_message = isset($metadata['description']) ? ($metadata['description'] . ' ' . $user->getLib()) :  'No log';
        // Ajout du log
        $contrat
            ->addUsersToNotify($user)
            ->addLog(
            (new ContratLogs())
                ->setTitle($title)
                ->setUser($user)
                ->setTransition($event->getTransition()->getName())
                ->setText($text_message)
            );

        $this->entityManager->persist($contrat);
        $this->entityManager->flush();
        // Notification des utilisateurs
        foreach ($contrat->getUsersToNotify() as $userToNotify) {
            $user = $this->userRepository->find($userToNotify);
            if($user) {
                $notif = (new Notifications())
                    ->setTitle($title)
                    ->setUser($user)
                    ->setObjectType(Notifications::OBJECT_TYPE_CONTRAT)
                    ->setLink('/contrat/consult/' . $contrat->getId())
                    ->setText($text_message);
                if ($contrat->getId()){
                    $notif->setObjectId($contrat->getId());
                }
                $this->entityManager->persist($notif);
            }
        }
        $this->entityManager->flush();

        // TODO : Ajouter un mail de notification
    }
    public static function getSubscribedEvents()
    {
        return [
            'workflow.contract_request.completed' => ['guardReview'],
        ];
    }
}