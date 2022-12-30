<?php

namespace App\Service\Contrat;

use App\Entity\Account\User;
use App\Entity\Contrat\ModeFacturation;
use App\Entity\Contrat\ModePaiement;
use App\Entity\Contrat\ModeRenouvellement;
use App\Entity\Contrat\PeriodicitePaiement;
use App\Entity\Contrat\TypeContrat;
use App\Service\Utils\StatutTraitConversion;
use Carbon\Carbon;
use Symfony\Component\Security\Core\Security;

class CreateContrat
{
    const DEFAULT_TEXT = 'Non renseigné';

    public function __construct(
        private StatutTraitConversion $statutTraitConversionSrv,
        private Security $security
    )
    {
    }

    public function __invoke(
        $data,
        $files
    )
    {

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

    }
}