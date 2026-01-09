<?php

namespace App\EventSubscriber;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class ContactSubscriber implements EventSubscriberInterface
{
    public function __construct(private HubInterface $hub) {}

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ];
    }

    public function postPersist(PostPersistEventArgs $args): void
    {
        $this->notifyMercure($args->getObject(), 'created');
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->notifyMercure($args->getObject(), 'updated');
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        $this->notifyMercure($args->getObject(), 'deleted');
    }

    private function notifyMercure(object $entity, string $action): void
    {
        // On ne s'intéresse qu'aux entités Contact
        if (!$entity instanceof Contact) {
            return;
        }

        // Ne pas notifier si le contact n'a pas de propriétaire
        if (!$entity->getOwner()) {
            return;
        }

        // Le "topic" : diffusion uniquement au propriétaire du contact
        $topic = 'user/' . $entity->getOwner()->getId() . '/contacts';

        // Construction du message (format Turbo Stream)
        $update = new Update(
            $topic,
            json_encode([
                '@id' => '/api/contacts/' . $entity->getId(),
                '@type' => 'Contact',
                'id' => $entity->getId(),
                'firstName' => $entity->getFirstName(),
                'lastName' => $entity->getLastName(),
                'phoneNumber' => $entity->getPrincipalPhoneNumber(),
                'action' => $action,
                'date' => (new \DateTime())->format('c')
            ])
        );

        // Publication sur Mercure
        $this->hub->publish($update);
    }
}
