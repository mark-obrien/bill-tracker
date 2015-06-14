<?php

namespace Application\Entity;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class EntityEventListener {

    /**
     * Listens to Entity event 'prePersist' - On initial Entity persist
     * - Sets current date and modified date
     * @param $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $this->updateDateFields($entity);
    }

    /**
     * Listens to Entity event 'preUpdate' - On existing Entity flush (db update)
     * @param $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $this->updateDateFields($entity);
    }

    /**
     * Updates an Entity date fields
     * - Sets current date and modified date
     * @param $entity
     */
    private function updateDateFields(&$entity)
    {
        // Only modify 'dateCreated' if property exists and value 'null'
        if(property_exists($entity,'dateAdded') && is_null($entity->dateAdded))
            $entity->dateAdded = new \DateTime("now");

        // Only modify 'dateModified' if property exists
        if(property_exists($entity,'dateModified'))
            $entity->dateModified = new \DateTime("now");
    }

}