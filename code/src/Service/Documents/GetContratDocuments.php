<?php

namespace App\Service\Documents;

use App\Entity\Contrat\Contrat;
use App\Entity\Contrat\Document;
use App\Repository\Contrat\DocumentRepository;

class GetContratDocuments
{
    public function __construct(
        private DocumentRepository $documentRepository
    )
    {
    }

    public function __invoke(
        Contrat $contrat,
    )
    {
        $documents = $this
            ->documentRepository
            ->findBy(['contrat' => $contrat]);

        return array_map(fn(Document $document) => $document->toArray(), $documents);
    }
}