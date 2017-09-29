<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Group;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /**
         *  ROLES UTILISATEUR DE L'APPLICATION
         */
        $grpUser = new Group('Utilisateur', ['ROLE_USER'], 'ROLE_USER');
        $grpAdmin = new Group('Administrateur', ['ROLE_ADMIN'], 'ROLE_ADMIN');
        $grpComptable = new Group('Comptable', ['ROLE_ACCOUNTANT'], 'ROLE_ACCOUNTANT');

        $manager->persist($grpUser);
        $manager->persist($grpAdmin);
        $manager->persist($grpComptable);
        $manager->flush();

        /**
         * ADMINISTRATEUR DE L'APPLICATION
         */
        $admin = new User();
        $admin->setPassword($this->generatePassWord($admin));
        $admin->setEmail('admin@yopmail.com');
        $admin->setEnabled(true);
        $admin->addGroup($grpAdmin);
        $admin->setLastName('Notong Lontchi');
        $admin->setFirstName('Gabriel');
        $admin->setLanguage('fr');
        $manager->persist($admin);
        $manager->flush();
    }

    private function generatePassWord(User $user)
    {
        return $this->container->get('security.password_encoder')->encodePassword($user, '123');
    }
}