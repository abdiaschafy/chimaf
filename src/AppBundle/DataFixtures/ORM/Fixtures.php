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
        $grpAdmin = new Group('Administrateur', ['ROLE_ADMIN', 'ROLE_STORE_KEEPER', 'ROLE_USER'], 'ROLE_ADMIN');
        $grpComptable = new Group('Comptable', ['ROLE_ACCOUNTANT'], 'ROLE_ACCOUNTANT');
        $grpMagasinier = new Group('Gestionnaire de stock', ['ROLE_STORE_KEEPER'], 'ROLE_STORE_KEEPER');

        $manager->persist($grpUser);
        $manager->persist($grpAdmin);
        $manager->persist($grpComptable);
        $manager->persist($grpMagasinier);
        $manager->flush();

        /**
         * UTILISATEURS PRINCIPAUX DE L'APPLICATION
         */

        $admin = new User();
        $admin->setPassword($this->generatePassWord($admin));
        $admin->setEmail('admin@yopmail.com');
        $admin->setEnabled(true);
        $admin->addGroup($grpAdmin);
        $admin->setLastName('Chafang Lontchi');
        $admin->setFirstName('Abdias');
        $admin->setLanguage('fr');
        $manager->persist($admin);

        $comptable = new User();
        $comptable->setPassword($this->generatePassWord($comptable));
        $comptable->setEmail('compta@yopmail.com');
        $comptable->setEnabled(true);
        $comptable->addGroup($grpComptable);
        $comptable->setLastName('Kenne Tsapi');
        $comptable->setFirstName('Lucas');
        $comptable->setLanguage('fr');
        $manager->persist($comptable);

        $manager->flush();

        /**
         * CATEGORIE DES PRODUITS VENDUS
         */
        $plomberie = new CategorieProduit('PLOMBERIE', 'fa-tree');
        $manager->persist($plomberie);
        $jeu = new CategorieProduit('JEU', 'fa-lightbulb-o');
        $manager->persist($jeu);
        $tube = new CategorieProduit('TUBE', 'fa-themeisle');
        $manager->persist($comptable);
        $metaux = new CategorieProduit('METAUX', 'fa-sun-o');
        $manager->persist($metaux);
        $vannes = new CategorieProduit('VANNES', 'fa-sun-o');
        $manager->persist($vannes);
        $scotch = new CategorieProduit('SCOTCH', 'fa-sun-o');
        $manager->persist($scotch);
        $bois = new CategorieProduit('BOIS', 'fa-sun-o');
        $manager->persist($bois);
        $securite = new CategorieProduit('SECURITE', 'fa-sun-o');
        $manager->persist($securite);
        $jet = new CategorieProduit('JET', 'fa-sun-o');
        $manager->persist($jet);
        $outilBureau = new CategorieProduit('OUTILS DE BUREAU', 'fa-sun-o');
        $manager->persist($outilBureau);
        $balais = new CategorieProduit('BALAIS', 'fa-sun-o');
        $manager->persist($balais);
        $rondPlein = new CategorieProduit('ROND PLEIN', 'fa-sun-o');
        $manager->persist($rondPlein);
        $tole = new CategorieProduit('TOLE', 'fa-sun-o');
        $manager->persist($tole);
        $corniere = new CategorieProduit('CORNIERE', 'fa-sun-o');
        $manager->persist($corniere);
        $autre = new CategorieProduit('AUTRE', 'fa-sun-o');
        $manager->persist($autre);
        $pointes = new CategorieProduit('POINTES', 'fa-sun-o');
        $manager->persist($pointes);
        $manager->flush();

        /**
         * PRODUITS VENDUS
         */
        $pointes80 = new Produit($pointes, 'PP80', 'PAQUET POINTES ORD.DE 80mm', 25, 25, '4000');
        $pointes90 = new Produit($pointes, '123C2', 'POINTES ORDINAIRES(PAQUET) 90mm', 25, 47, '4000');
        $manager->persist($pointes80);
        $manager->persist($pointes90);

        $autre1 = new Produit($autre, 'BCT1KG', 'BOITE COLLE TANGIT DE 1kg', 25, 36, '12500');
        $autre2 = new Produit($autre, 'BPD35', 'BRONZE PLEIN : diamètre 35', 25, 96, '135000');
        $autre3 = new Produit($autre, 'BPD40', 'BRONZE PLEIN : diamètre 40', 25, 85, '174000');
        $autre4 = new Produit($autre, 'BPD60', 'BRONZE PLEIN : diamètre 60', 25, 145, '384000');
        $autre5 = new Produit($autre, 'CV80', 'CADENAS VACHETTE DE 80mm HARDENED SHACKLE', 25, 63, '15000');
        $autre6 = new Produit($autre, 'CUAT', 'CARTE UNIVERSELLE AVEC TELECOMMANDE', 25, 45, '14500');
        $autre7 = new Produit($autre, 'CAD99', 'COLLE ADHESIVE DURABON 99 EN 1kg', 25, 278, '175');
        $manager->persist($autre1);
        $manager->persist($autre2);
        $manager->persist($autre3);
        $manager->persist($autre4);
        $manager->persist($autre5);
        $manager->persist($autre6);
        $manager->persist($autre7);

        $corniere1 = new Produit($corniere, 'COR30', 'CORNIERE DE 30', 25, 47, '8650');
        $corniere2 = new Produit($corniere, 'COR40', 'CORNIERE DE 40', 25, 25, '12500');
        $corniere3 = new Produit($corniere, 'SGGM3', 'SABLE GROS GRAIN EN m3', 25, 24, '12500');
        $corniere4 = new Produit($corniere, 'RTMCVL120', 'ROULEAU TOILE MOUSTIQUAIRE COULEUR VERTE LARG : 1,20m (plast.de 25m) en mètre ', 25, 278, '900');
        $manager->persist($corniere1);
        $manager->persist($corniere2);
        $manager->persist($corniere3);
        $manager->persist($corniere4);

        $tole1 = new Produit($tole, 'TAPN3m', 'TÔLE ACIER PLANE NOIRE 2000X1000.épaisseur3mm', 25, 23, '48500');
        $tole2 = new Produit($tole, 'TAPN2m', 'TÔLE ACIER PLANE NOIRE 2000X1000.épaisseur2mm', 25, 78, '29000');
        $tole3 = new Produit($tole, 'TFB30', 'TOLE FAITIERE BORD de 30', 25, 14, '5000');
        $tole4 = new Produit($tole, 'TB610X6m', 'TOLE BAC de 6/10e X6m', 25, 41, '34500');
        $manager->persist($tole1);
        $manager->persist($tole2);
        $manager->persist($tole3);
        $manager->persist($tole4);

        $balais1 = new Produit($balais, 'BCO30cm', 'BALAIS COCO DE 30 cm', 25, 9, '1300');
        $balais2 = new Produit($balais, 'BCO40cm', 'BALAIS COCO DE 40 cm', 25, 5, '1400');
        $balais3 = new Produit($balais, 'BCOTN', 'BALAIS CONTONNIER', 25, 45, '3800');
        $balais4 = new Produit($balais, 'BPDR', 'BALAIS PAILLE DE RIZ', 25, 47, '6800');
        $balais5 = new Produit($balais, 'MAROC', 'MACHETTE(CROCO)', 25, 20, '3500');
        $manager->persist($balais1);
        $manager->persist($balais2);
        $manager->persist($balais3);
        $manager->persist($balais4);
        $manager->persist($balais5);

        $jet1 = new Produit($jet, 'JBD70', 'JET EN BRONZE diamètre 70', 25, 65, '235000');
        $jet2 = new Produit($jet, 'JBD50', 'JET EN BRONZE diamètre 50', 25, 97, '95000');
        $jet3 = new Produit($jet, 'JAP40X1m', 'JET ACIER PLEIN DE 40X1m', 25, 278, '27000');
        $jet4 = new Produit($jet, 'JAP60X1m', 'JET ACIER PLEIN DE 60X1m', 25, 58, '40000');
        $jet5 = new Produit($jet, 'JAP35X1m', 'JET EN INOX DE 35mmX1m', 25, 278, '80000');
        $jet6 = new Produit($jet, 'JAP80X1m', 'JET ACIER PLEIN 80X1m', 25, 145, '100000');
        $manager->persist($jet1);
        $manager->persist($jet2);
        $manager->persist($jet3);
        $manager->persist($jet4);
        $manager->persist($jet5);
        $manager->persist($jet6);

        $outilBureau1 = new Produit($outilBureau, 'OTEAGR', 'OTE-AGRAFFE', 25, 233, '1200');
        $outilBureau2 = new Produit($outilBureau, 'POST', 'PORTE STYLOS', 25, 100, '2500');
        $outilBureau3 = new Produit($outilBureau, 'RG300P', 'REGISTRE DE 300 PAGES', 25, 47, '7000');
        $outilBureau4 = new Produit($outilBureau, 'FB2300RPG', 'FEUTRE BIC 2300 (rouge) POUR GRAVURE', 25, 22, '600');
        $outilBureau5 = new Produit($outilBureau, 'CCPC', 'CHEMISE CARTONNEE POUR CLASSEMENT', 25, 33, '350');
        $outilBureau6 = new Produit($outilBureau, 'ENCRO', 'ENCRIER ROUGE', 25, 278, '1000');
        $outilBureau7 = new Produit($outilBureau, 'BCRB50', 'BIC COULEUR ROUGE BOITE DE 50', 25, 56, '160');
        $outilBureau8 = new Produit($outilBureau, 'BCBB50', 'BIC COULEUR BLEUE BOITE DE 50', 25, 87, '160');
        $outilBureau9 = new Produit($outilBureau, 'BCNB50', 'BIC COULEUR NOIRE BOITE DE 50', 25, 44, '160');
        $manager->persist($outilBureau1);
        $manager->persist($outilBureau2);
        $manager->persist($outilBureau3);
        $manager->persist($outilBureau4);
        $manager->persist($outilBureau5);
        $manager->persist($outilBureau6);
        $manager->persist($outilBureau7);
        $manager->persist($outilBureau8);
        $manager->persist($outilBureau9);

        $securite1 = new Produit($securite, 'CV54mm', 'CADENAS VACHETTE DE 54 mm', 25, 99, '9000');
        $securite2 = new Produit($securite, 'SCV3458C12', 'SERRURE A CANON VACHETTE 3458-C-12/LAPERCHE', 25, 72, '35000');
        $securite3 = new Produit($securite, 'SDWC', 'SERRURE DE DOUCHE « WC »', 25, 200, '15000');
        $securite4 = new Produit($securite, 'CUHRR22', 'COMPRESSEURS UH ROTATIFS R22. 3CV', 25, 47, '215000');
        $securite5 = new Produit($securite, 'CUHPR22', 'COMPRESSEURS UH A PISTON R22. 3CV-240V', 25, 24, '285000');
        $securite6 = new Produit($securite, 'CV131380m', 'CADENAS VACHETTE 1313-80mm', 25, 120, '11500');
        $securite7 = new Produit($securite, 'CLSN15', 'CLIMATISSEUR SPLIT NASCO. 1,5CV', 25, 15, '280000');
        $manager->persist($securite1);
        $manager->persist($securite2);
        $manager->persist($securite3);
        $manager->persist($securite4);
        $manager->persist($securite5);
        $manager->persist($securite6);
        $manager->persist($securite7);

        $bois1 = new Produit($bois, 'PSAP22X4', 'PLANCHE (SAPPEL) 220X40', 25, 278, '11850');
        $bois2 = new Produit($bois, 'CPSAP8mm', 'CONTRE PLAQUET(SAPPEL) DE 8 mm', 25, 245, '17500');
        $bois3 = new Produit($bois, 'DICELSM', 'DILUANT CELLULOSIQUE(en litre) smalto', 25, 235, '1500');
        $bois4 = new Produit($bois, 'VEBRCELSM', 'VERNIS BRILLANTT CELLULOSIQUE en litre smalto', 25, 147, '7500');
        $bois5 = new Produit($bois, 'FODCELSM', 'FOND DUR CELLULOSIQUE smalto', 25, 18, '7800');
        $bois6 = new Produit($bois, 'TAAC', 'TAMBOUR AUTO-COLLANT', 25, 278, '4500');
        $bois7 = new Produit($bois, 'PAU120', 'PAQUET AUTO-COLLANT DE 120', 25, 789, '25000');
        $bois8 = new Produit($bois, 'PAU80', 'PAQUET AUTO-COLLANT DE 80', 25, 123, '25000');
        $bois9 = new Produit($bois, 'PAU240', 'PAQUET AUTO-COLLANT DE 240', 25, 278, '25000');
        $manager->persist($bois1);
        $manager->persist($bois2);
        $manager->persist($bois3);
        $manager->persist($bois4);
        $manager->persist($bois5);
        $manager->persist($bois6);
        $manager->persist($bois7);
        $manager->persist($bois8);
        $manager->persist($bois9);

        $manager->flush();
    }

    private function generatePassWord(User $user)
    {
        return $this->container->get('security.password_encoder')->encodePassword($user, '123');
    }
}