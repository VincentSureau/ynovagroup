<?php

namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use App\Entity\Company;
use App\Entity\Files;
use App\Entity\User;

use App\DataFixtures\Faker\MovieAndGenreProvider;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Utils\Slugger;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $slugger;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, Slugger $slugger)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        // Roles
        $roleAdmin = new Role();
        $roleAdmin->setCode('ROLE_ADMIN');
        $roleAdmin->setName('Admin');

        $roleUser = new Role();
        $roleUser->setCode('ROLE_MEMBER');
        $roleUser->setName('Membre');

        $roleDeveloper = new Role();
        $roleDeveloper->setCode('ROLE_DEVELOPER');
        $roleDeveloper->setName('Chargé de développement');

        $roleCommercial = new Role();
        $roleCommercial->setCode('ROLE_BUSINESS');
        $roleCommercial->setName('Commercial');

        $manager->persist($roleAdmin);
        $manager->persist($roleUser);
        $manager->persist($roleDeveloper);
        $manager->persist($roleCommercial);


        // Utilisateurs
        $admin = new User();
        $admin->setEmail('admin@ynovagroup.fr');
        $admin->setRole($roleAdmin);
        $encodedPassword = $this->passwordEncoder->encodePassword($admin, 'admin');
        $admin->setPassword($encodedPassword);
        $admin->setFirstname('admin');
        $admin->setLastname('admin');

        $user = new User();
        $user->setEmail('user@ynovagroup.fr');
        $user->setRole($roleUser);
        $encodedPassword = $this->passwordEncoder->encodePassword($user, 'user');
        $user->setPassword($encodedPassword);
        $user->setFirstname('user');
        $user->setLastname('user');

        $developer = new User();
        $developer->setEmail('developer@ynovagroup.fr');
        $developer->setRole($roleDeveloper);
        $encodedPassword = $this->passwordEncoder->encodePassword($developer, 'developer');
        $developer->setPassword($encodedPassword);
        $developer->setFirstname('developer');
        $developer->setLastname('developer');

        $commercial = new User();
        $commercial->setEmail('commercial@ynovagroup.fr');
        $commercial->setRole($roleCommercial);
        $encodedPassword = $this->passwordEncoder->encodePassword($commercial, 'commercial');
        $commercial->setPassword($encodedPassword);
        $commercial->setFirstname('commercial');
        $developer->setLastname('commercial');

        




        $user = new User();
        $user->setEmail('user@ynovagroup.fr');
        $user->setUsername('user');
        $user->setRole($roleUser);
        $encodedPassword = $this->passwordEncoder->encodePassword($user, 'user');
        $user->setPassword($encodedPassword);

        $developer = new User();
        $developer->setEmail('developer@ynovagroup.fr');
        $developer->setUsername('developer');
        $developer->setRole($roleDeveloper);
        $encodedPassword = $this->passwordEncoder->encodePassword($developer, 'developer');
        $developer->setPassword($encodedPassword);

        $commercial = new User();
        $commercial->setEmail('commercial@ynovagroup.fr');
        $commercial->setUsername('commercial');
        $commercial->setRole($roleCommercial);
        $encodedPassword = $this->passwordEncoder->encodePassword($commercial, 'commercial');
        $commercial->setPassword($encodedPassword);

        $manager->persist($admin);
        $manager->persist($user);
        $manager->persist($developer);
        $manager->persist($commercial);

        // Pharmacies
        $company = new Company();
        $company->setName('Pharmacie de Paris');
        $company->setFirstAdressField('5 rue de pourquoi pas');
        $company->setsecondAdressField('Second champ vide');
        $company->setPostalCode('75000');
        $company->setCity('Paris');
        $company->setCountry('France');
        $company->setDescription('Description factice');
        $company->setCommercial(3);




        $manager->flush();
    }
}
