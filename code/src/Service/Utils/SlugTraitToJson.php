<?php

namespace App\Service\Utils;

use App\Entity\Traits\SlugTrait;
use App\Entity\Traits\SlugTraitImpl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class SlugTraitToJson
{
    public function __construct(private EntityManagerInterface $manager)
    {
    }

    public function __invoke($user, $classToSearch): Response
    {
        $data = $this->manager->getRepository($classToSearch)->findAll();

        return new Response(
            json_encode(array_map(function ($item) {
                return [
                    'value' => $item->getId(),
                    'label' => $item->getLib(),
                ];
            }, $data)), Response::HTTP_OK, [
                'Content-Type' => 'application/json',
                'Cache-Control' => 'max-age=3600',
            ]
        );
    }
}