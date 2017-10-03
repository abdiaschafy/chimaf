<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\CategorieProduit;
use AppBundle\Entity\Group;
use AppBundle\Entity\Produit;
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
        $manager->flush();

        /**
         * CATEGORIE DES PRODUITS VENDUS
         */
        $charbon = new CategorieProduit('Charbon', 'fa-tree');
        $elect = new CategorieProduit('Electricité', 'fa-lightbulb-o');
        $comestible = new CategorieProduit('Comestibles', 'fa-themeisle');
        $sucre = new CategorieProduit('Sucre', 'fa-sun-o');
        $manager->persist($elect);
        $manager->persist($charbon);
        $manager->persist($comestible);
        $manager->persist($sucre);
        $manager->flush();

        /**
         * PRODUITS VENDUS
         */
        $produitCharbon1 = new Produit($charbon, '123C1', 'Charbon et lignite', 25, 278, '2510');
        $produitCharbon2 = new Produit($charbon, '123C2', 'tourbe', 25, 278, '783.6');
        $produitCharbon3 = new Produit($charbon, '123C3', 'bois de chauffe', 25, 278, '175');
        $produitCharbon4 = new Produit($charbon, '123C4', 'briquettes', 25, 278, '362.85');
        $manager->persist($produitCharbon1);
        $manager->persist($produitCharbon2);
        $manager->persist($produitCharbon3);
        $manager->persist($produitCharbon4);

        $produitElect1 = new Produit($elect, '0014E1', 'Electricité distribuée', 23, 415, '3500');
        $produitElect2 = new Produit($elect, '0014E2', 'Eau chaude', 23, 415, '254');
        $produitElect3 = new Produit($elect, '0014E3', 'vapeur', 23, 415, '14000');
        $manager->persist($produitElect1);
        $manager->persist($produitElect2);
        $manager->persist($produitElect3);

        $prodComestible1 = new Produit($comestible, '14COM1', 'bois bruts ou sciés', 5, 157, '514.15');
        $prodComestible2 = new Produit($comestible, '14COM2', 'Liège brut', 5, 157, '259');
        $prodComestible3 = new Produit($comestible, '14COM3', 'Caoutchouc brut naturel', 5, 157, '3452');
        $manager->persist($prodComestible1);
        $manager->persist($prodComestible2);
        $manager->persist($prodComestible3);

        $prodSucre1 = new Produit($sucre, '45SUC01', 'Sucre', 178, 2587, '1100');
        $prodSucre2 = new Produit($sucre, '45SUC02', 'chocolat', 19, 456, '2451');
        $prodSucre3 = new Produit($sucre, '45SUC03', 'Confiserie', 19, 456, '1536');
        $manager->persist($prodSucre1);
        $manager->persist($prodSucre2);
        $manager->persist($prodSucre3);

        $manager->flush();
    }

    private function generatePassWord(User $user)
    {
        return $this->container->get('security.password_encoder')->encodePassword($user, '123');
    }
}