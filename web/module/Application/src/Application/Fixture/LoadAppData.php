<?php

namespace Application\Fixture;

use Application\Entity\Role;
use Application\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Events;
use Application\Entity\EntityEventListener;


class LoadAppData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Attach Event Listener to Doctrine Object Manager
        $manager->getEventManager()->addEventListener(array(Events::prePersist), new EntityEventListener());


        $roles = $this->createRoles($manager);
        $users = $this->createUsers($manager, $roles);

        $manager->flush();
    }

    private function createRoles($manager)
    {

        $role1 = new Role();
        $role1->name = 'Admin';
        $manager->persist($role1);

        $role2 = new Role();
        $role2->name = 'Consultant';
        $manager->persist($role2);

        $role3 = new Role();
        $role3->name = 'Franchisor';
        $manager->persist($role3);

        return array('admin'=>$role1, 'consultant'=>$role2, 'franchisor'=>$role3);
    }

    private function createUsers($manager, $roles)
    {
        $adminUser = new User();
        $adminUser->id = 10001;
        $adminUser->username = 'beiadmin@blueearth.net';
        $adminUser->firstName = 'Reginald';
        $adminUser->lastName = 'BlueEarth';
        $adminUser->email = 'beiadmin@blueearth.net';
        $adminUser->active = true;
        $adminUser->setPassword('000FFerf');

        $adminUser->role = $roles['admin'];
        $manager->persist($adminUser);

        $metaData = $manager->getClassMetadata(get_class($adminUser));
        $metaData->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);

        return array('admin'=>$adminUser);
    }


}