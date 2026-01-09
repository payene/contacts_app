<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Contact;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ContactOwnerProcessor implements ProcessorInterface
{
    public function __construct(
        // On injecte le processeur Doctrine original
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        // On injecte le service Security pour récupérer l'utilisateur connecté
        private Security $security
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        // On ne s'intéresse qu'aux entités Contact
        if (!$data instanceof Contact) {
            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        // Si le contact n'a pas déjà de propriétaire, on définit l'utilisateur connecté
        if (null === $data->getOwner()) {
            $data->setOwner($this->security->getUser());
        }

        // On passe au processeur Doctrine pour la persistance
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
