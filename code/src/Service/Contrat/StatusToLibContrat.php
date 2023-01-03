<?php

namespace App\Service\Contrat;

use Symfony\Component\Workflow\WorkflowInterface;

class StatusToLibContrat
{
    public function __construct(
        private WorkflowInterface $contractRequestStateMachine
    )
    {
    }

    public function __invoke(
        string $status
    ): array
    {
        $data = $this->contractRequestStateMachine->getMetadataStore()->getPlaceMetadata($status);
        return [
            'label' => $data['label'],
            'color' => $data['color'],
            'type' => 'badge'
        ];
    }
}