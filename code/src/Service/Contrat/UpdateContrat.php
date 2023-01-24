<?php

namespace App\Service\Contrat;

use App\Entity\Contrat\Contrat;
use App\Entity\Contrat\ModeFacturation;
use App\Entity\Contrat\ModePaiement;
use App\Entity\Contrat\ModeRenouvellement;
use App\Entity\Contrat\PeriodicitePaiement;
use App\Entity\Contrat\TypeContrat;
use App\Service\Utils\StatutTraitConversion;
use Doctrine\ORM\EntityManagerInterface;

class UpdateContrat
{
    const DEFAULT_TEXT = 'Non renseigné';

    public function __construct(
        private StatutTraitConversion $statutTraitConversionSrv,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function __invoke(
        Contrat $contrat,
        array $data,
    ) : bool
    {
        try{
            $oldObject = clone $contrat;
            $delai_denonciation = $data['delai-denonciation'] ?? 1;
            $type_contrat = ($this->statutTraitConversionSrv)($data['type-contrat'] ?? self::DEFAULT_TEXT, TypeContrat::class);
            $mode_paiement = ($this->statutTraitConversionSrv)($data['mode-paiement'] ?? self::DEFAULT_TEXT, ModePaiement::class);
            $mode_facturation = ($this->statutTraitConversionSrv)($data['mode-facturation'] ?? self::DEFAULT_TEXT, ModeFacturation::class);
            $mode_renouvellement = ($this->statutTraitConversionSrv)($data['mode-renouvellement'] ?? self::DEFAULT_TEXT, ModeRenouvellement::class);
            $periodicite_paiement = ($this->statutTraitConversionSrv)($data['periodicite-paiement'] ?? self::DEFAULT_TEXT, PeriodicitePaiement::class);

            $contrat->setDelaiDenonciationPreavis($delai_denonciation);
            $contrat->setTypeContrat($type_contrat);
            $contrat->setModePaiement($mode_paiement);
            $contrat->setModeFacturation($mode_facturation);
            $contrat->setModeRenouvellement($mode_renouvellement);
            $contrat->setPeriodicitePaiement($periodicite_paiement);

            $contrat->setObjet(
                $data['objet'] ?? $oldObject->getObjet()
            );
            $contrat->setIdentiteConcontractant(
                $data['identite-cocontractant'] ?? $oldObject->getIdentiteConcontractant()
            );
            $contrat->setClausesParticulieres(
                $data['clauses-particulieres'] ?? $oldObject->getClausesParticulieres()
            );
            $contrat->setDateEntreeVigueur(
                (\DateTime::createFromFormat('d/m/Y', $data['date-entree-vigueur'])) ?? $oldObject->getDateEntreeVigueur()
            );
            $contrat->setDateFinContrat(
                (\DateTime::createFromFormat('d/m/Y', $data['date-fin-contrat'])) ?? $oldObject->getDateFinContrat()
            );
            $contrat->setObjetConditionsModifications(
                $data['objet-modification'] ?? $oldObject->getObjetConditionsModifications()
            );
            $contrat->setDetailsConditionsModifications(
                $data['details-modification'] ?? $oldObject->getDetailsConditionsModifications()
            );

            /*dump(
                $contrat,
                $delai_denonciation,
                $type_contrat,
                $mode_paiement,
                $mode_facturation,
                $mode_renouvellement,
                $periodicite_paiement,
            );*/

            $this->entityManager->persist($contrat);
            $this->entityManager->flush();
            return true;
        }catch (\Exception $e){
            return false;
        }
    }
}