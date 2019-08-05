<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Rss;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Files;

use App\Entity\Config;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // $developper = new User();
        // $developper
        //     ->setFirstname($faker->firstname)
        //     ->setLastname($faker->name)
        //     ->setEmail('dev@exemple.fr')
        //     ->setRoles(['ROLE_DEVELOPER'])
        //     ->setPassword($this->passwordEncoder->encodePassword($developper, 'admin'));
        
        // $manager->persist($developper);
        


        // $commercial = new User();
        // $commercial
        //     ->setEmail('commercial@commercial.fr')
        //     ->setRoles(['ROLE_BUSINESS'])
        //     ->setPassword($this->passwordEncoder->encodePassword($commercial, 'commercial'))
        //     ->setFirstname($faker->firstName)
        //     ->setLastname($faker->lastName);
        
        // $manager->persist($commercial);

        // // Pharmacies
        // $pharmacies = [];
        // for($i = 0; $i <= 40; $i++) {
        //     $company = new Company();
        //     $company
        //         ->setName($faker->company)
        //         ->setFirstAdressField($faker->streetAddress)
        //         ->setsecondAdressField($faker->streetName)
        //         ->setPostalCode($faker->postcode)
        //         ->setCity($faker->city)
        //         ->setCountry('France')
        //         ->setDescription('Description factice')
        //         ->setCommercial($commercial)
        //         ->setIsActive(true)
        //         ->setCreatedAt(new \Datetime)
        //         ->setUpdatedAt(new \Datetime)
        //         ;
        //     $manager->persist($company);
        //     $pharmacies[] = $company;
        // }

        // $users = [];
        // foreach($pharmacies as $pharma) {
        //     $user = new User();
        //     $user
        //         ->setEmail($faker->unique()->email)
        //         ->setRoles(['ROLE_MEMBER'])
        //         ->setPassword($this->passwordEncoder->encodePassword($user, 'user'))
        //         ->setFirstname($faker->firstName)
        //         ->setLastname($faker->lastName)
        //         ->setCompany($pharma)
        //         ->setIsActive(true)
        //         ->setCreatedAt(new \Datetime)
        //         ->setUpdatedAt(new \Datetime)
        //     ;

        //     $manager->persist($user);
        //     $users[] = $user;
        // }


        // Utilisateurs
        $admin = new User();
        $admin
            ->setEmail('stephane@babicz.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'admin'))
            ->setFirstname('Stéphane')
            ->setLastname('Babicz')
            ->setIsActive(true)
            ->setCreatedAt(new \Datetime)
            ->setUpdatedAt(new \Datetime)
            ;

        $manager->persist($admin);

        $rsslinks = [
            'https://www.who.int/feeds/entity/mediacentre/news/fr/rss.xml',
            'https://www.santemagazine.fr/feeds/rss',
            'https://www.e-sante.fr/taxonomy/term/615086/feed',
            'https://www.e-sante.fr/taxonomy/term/615087/feed'
        ];

        foreach($rsslinks as $link) {
            $rssFeed = new Rss();

            $rssFeed
                ->setName($link)
                ->setLink($link)
                ->setIsActive(true);

            $manager->persist($rssFeed);
        }

        $config = new Config();

        $config
            ->setIsUnderMaintenance(false)
            ->setCreatedAt(new \Datetime)
            ->setCreatedAt(new \Datetime)
            ->setUpdatedAt(new \Datetime);

        $manager->persist($config);

        $manager->flush();
    }
}
