<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\Files;
use Faker\Factory;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

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

        $developper = new User();
        $developper
            ->setFirstname($faker->firstname)
            ->setLastname($faker->name)
            ->setEmail('dev@exemple.fr')
            ->setRoles(['ROLE_DEVELOPER'])
            ->setPassword($this->passwordEncoder->encodePassword($developper, 'admin'));
        
        $manager->persist($developper);
        


        $commercial = new User();
        $commercial
            ->setEmail('commercial@commercial.fr')
            ->setRoles(['ROLE_BUSINESS'])
            ->setPassword($this->passwordEncoder->encodePassword($commercial, 'commercial'))
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName);
        
        $manager->persist($commercial);

        // Pharmacies
        $pharmacies = [];
        for($i = 0; $i <= 40; $i++) {
            $company = new Company();
            $company
                ->setName($faker->company)
                ->setFirstAdressField($faker->streetAddress)
                ->setsecondAdressField($faker->streetName)
                ->setPostalCode($faker->postcode)
                ->setCity($faker->city)
                ->setCountry('France')
                ->setDescription('Description factice')
                ->setCommercial($commercial)
                ->setIsActive(true)
                ->setCreatedAt(new \Datetime)
                ->setUpdatedAt(new \Datetime)
                ;
            $manager->persist($company);
            $pharmacies[] = $company;
        }

        $users = [];
        foreach($pharmacies as $pharma) {
            $user = new User();
            $user
                ->setEmail($faker->unique()->email)
                ->setRoles(['ROLE_MEMBER'])
                ->setPassword($this->passwordEncoder->encodePassword($user, 'user'))
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setCompany($pharma)
                ->setIsActive(true)
                ->setCreatedAt(new \Datetime)
                ->setUpdatedAt(new \Datetime)
            ;

            $manager->persist($user);
            $users[] = $user;
        }


        // Utilisateurs
        $admin = new User();
        $admin
            ->setEmail('admin@admin.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'admin'))
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setIsActive(true)
            ->setCreatedAt(new \Datetime)
            ->setUpdatedAt(new \Datetime)
            ;

        $manager->persist($admin);
        
        // Posts
        $posts = [];
        for($i = 0; $i <= 30; $i++) {
            $post = new Post();
            $post
                ->setTitle($faker->word(5, true))
                ->setContent($faker->paragraphs(6, true))
                ->setIsActive(true)
                ->setCreatedAt(new \Datetime)
                ->setUpdatedAt(new \Datetime)
                ->setAuthor($admin)
                ;
            $manager->persist($post);
            $posts[] = $post;
        }

        $files = [];
        for($i = 0; $i <= 50; $i++) {
            $file = new Files();
            $file
                ->setName($faker->words(4, true))
                ->setDescription($faker->word(30))
                ->setType($faker->fileExtension)
                ->setPath($faker->image)
                ->setCommercial($commercial)
                ->setCreatedAt(new \Datetime)
                ->setUpdatedAt(new \Datetime)
                ->setIsActive(true)
                ;
            $manager->persist($file);
            $files[] = $file;
        }

        foreach($files as $file) {
            for($i = 0; $i <= 6; $i++) {
                $file->addPharmacy($pharmacies[array_rand($pharmacies)]);
            }
        }

        $manager->flush();
    }
}
