<?php

namespace App\Service\Contrat;

use App\Entity\Account\Departement;
use App\Entity\Account\User;
use App\Entity\Contrat\Contrat;
use App\Entity\Contrat\Document;
use App\Entity\Contrat\ModeFacturation;
use App\Entity\Contrat\ModePaiement;
use App\Entity\Contrat\ModeRenouvellement;
use App\Entity\Contrat\PeriodicitePaiement;
use App\Entity\Contrat\TypeContrat;
use App\Service\Utils\StatutTraitConversion;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class CreateContrat
{
    const DEFAULT_TEXT = 'Non renseigné';

    public function __construct(
        private StatutTraitConversion $statutTraitConversionSrv,
        private Security $security,
        private EntityManagerInterface $entityManager,
        private ParameterBagInterface $params,
        private SluggerInterface $slugger,
        private WorkflowInterface $contractRequestStateMachine
    )
    {
    }

    public function __invoke(
        $data,
        $files
    ) : bool
    {
        /** @var User $user */
        $user = $this->security->getUser();

        //Champs textes
        $objet = $data['objet'] ?? self::DEFAULT_TEXT;
        $identite_cocontractant = $data['identite-cocontractant'] ?? self::DEFAULT_TEXT;
        $clauses_particulieres = $data['clauses-particulieres'] ?? self::DEFAULT_TEXT;
        $objet_modification = $data['objet-modification'] ?? self::DEFAULT_TEXT;
        $details_modification = $data['details-modification'] ?? self::DEFAULT_TEXT;

        $date_entree_vigueur = isset($data['date-entree-vigueur']) ? (\DateTime::createFromFormat('d/m/Y', $data['date-entree-vigueur'])) : new Carbon();
        $date_fin_contrat = isset($data['date-fin-contrat']) ? (\DateTime::createFromFormat('d/m/Y', $data['date-fin-contrat'])) : new Carbon();

        $delai_denonciation = $data['delai-denonciation'] ?? 1;
        $type_contrat = ($this->statutTraitConversionSrv)($data['type-contrat'] ?? self::DEFAULT_TEXT, TypeContrat::class);
        $mode_paiement = ($this->statutTraitConversionSrv)($data['mode-paiement'] ?? self::DEFAULT_TEXT, ModePaiement::class);
        $mode_facturation = ($this->statutTraitConversionSrv)($data['mode-facturation'] ?? self::DEFAULT_TEXT, ModeFacturation::class);
        $mode_renouvellement = ($this->statutTraitConversionSrv)($data['mode-renouvellement'] ?? self::DEFAULT_TEXT, ModeRenouvellement::class);
        $periodicite_paiement = ($this->statutTraitConversionSrv)($data['periodicite-paiement'] ?? self::DEFAULT_TEXT, PeriodicitePaiement::class);

        $departement = $user->getDepartement();
        if($this->checkPerm($user)){
            if (isset($data['departement-initiateur'])){
                if(is_numeric($data['departement-initiateur'])){
                    $departement = $this->entityManager
                    ->getRepository(Departement::class)
                    ->findOneBy(
                        ['id' => intval($data['departement-initiateur'])]
                    );
                }
            }
        }

        $contrat = new Contrat();
        $contrat->setObjet($objet);
        $contrat->setIdentiteConcontractant($identite_cocontractant);
        $contrat->setClausesParticulieres($clauses_particulieres);
        $contrat->setObjetConditionsModifications($objet_modification);
        $contrat->setDetailsConditionsModifications($details_modification);
        $contrat->setDateEntreeVigueur($date_entree_vigueur);
        $contrat->setDateFinContrat($date_fin_contrat);
        $contrat->setDelaiDenonciationPreavis($delai_denonciation);
        $contrat->setTypeContrat($type_contrat);
        $contrat->setModePaiement($mode_paiement);
        $contrat->setModeFacturation($mode_facturation);
        $contrat->setModeRenouvellement($mode_renouvellement);
        $contrat->setPeriodicitePaiement($periodicite_paiement);
        $contrat->setDepartementInitiateur($departement);
        $contrat->setOwnedBy($user);
        $contrat->setCurrentState($contrat::CREATED);

        foreach ($files as $file) {
            $slugger =
            // Move file to /public/uploads/contrats/pj
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->params->get('contrat_pj_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                throw new \Exception('Fichier corrompu !');
            }


            $contrat->addDocument(
                (new Document())
                    ->setFilename($newFilename)
                    ->setFiletype("")
                ->setLocation($this->params->get('contrat_pj_directory_client') . '/' . $newFilename)
            );
        }
        // Une demande de contrat initiée par un admin ou un membre du juridique devient automatiquement un contrat
        $transition = $this->checkPerm($user) ? 'submit_by_juridique' : 'submit';
        $this->contractRequestStateMachine->apply($contrat, $transition);
        $this->entityManager->persist($contrat);
        $this->entityManager->flush();
        return true;
    }

    private function checkPerm(User $user): bool{
        $roles = $user->getRoles();
        return
            in_array('ROLE_USER_JURIDIQUE', $roles) ||
            in_array('ROLE_MANAGER_JURIDIQUE', $roles) ||
            in_array('ROLE_ADMIN', $roles);
    }
}