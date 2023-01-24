<?php

namespace App\Service\Utils;

use App\Entity\Account\User;
use App\Entity\Traits\SlugTrait;
use App\Entity\Traits\SlugTraitImpl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class SlugTraitToJson
{
    public function __construct(
        private EntityManagerInterface $manager,
        private Security $security
    )
    {
    }

    public function __invoke($user, $classToSearch, $giveArray = false, $removeDefault = false): Response|array
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $data = $this->manager->getRepository($classToSearch)->findAll();

        if (!$removeDefault) {
            $filteredData = array_filter($data, function ($item) use ($user, $classToSearch) {
                return
                    ($item->getIsListable() == true && $item->getLib() != 'Non renseigné')
                    ||
                    (!$item->IS_USER_LIMITED && in_array($user->getId(), $item->getUsers()));
            });
        }else{
            $filteredData = $data;
        }

        $result = array_map(function ($item) {
            return [
                'value' => $item->getId(),
                'label' => $item->getLib(),
            ];
        }, $filteredData);

        if ($giveArray) {
            return $result;
        }

        // if array is associative, convert it to indexed array
        $result = array_values($result);

        return new JsonResponse($result, Response::HTTP_OK, [
                'Content-Type' => 'application/json',
                'Cache-Control' => 'max-age=3600',
            ]
        );

    }

    function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

}