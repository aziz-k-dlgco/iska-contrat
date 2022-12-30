<?php

namespace App\Service\Utils;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class StatutTraitConversion
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security
    )
    {
    }

    public function __invoke(
        $data, $class
    )
    {
        try {
            if (!is_numeric($data)){
                $slugify = new Slugify();
                // Vérification de l'existence de l'objet en base
                $object = $this->entityManager
                    ->getRepository($class)
                    ->findOneBy(
                        ['slug' => $slugify->slugify($data)]
                    );

                if ($object) {
                    if($object->getLib === 'Non renseigné'){
                        return $object;
                    }
                    $object->addUser($this->security->getUser());
                    $this->entityManager->persist($object);
                    $this->entityManager->flush();
                    return $object;
                }

                // l'objet n'existe pas en base, on le crée
                $reflectionClass = new \ReflectionClass($class);
                $statut = $reflectionClass->newInstance();
                $statut->setLib($data);
                $statut->addUser($this->security->getUser());
                $this->entityManager->persist($statut);
                $this->entityManager->flush();
                return $statut;
            }else{
                $object = $this->entityManager
                    ->getRepository($class)
                    ->findOneBy(
                        ['id' => $data]
                    );
                if ($object) {
                    return $object;
                }else{
                    return $this->entityManager
                        ->getRepository($class)
                        ->findOneBy(
                            ['slug' => 'non-renseigne']
                        );
                }
            }
        }catch (\Exception $e){
            return $this->entityManager
                ->getRepository($class)
                ->findOneBy(
                    ['slug' => 'non-renseigne']
                );
        }
    }
}