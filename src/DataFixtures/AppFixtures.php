<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\User;
use App\Entity\Post;

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
        // Pharmacies
        $company1 = new Company();
        $company1->setName('Pharmacie de Paris');
        $company1->setFirstAdressField('5 rue de pourquoi pas');
        $company1->setsecondAdressField('Second champ vide');
        $company1->setPostalCode('75000');
        $company1->setCity('Paris');
        $company1->setCountry('France');
        $company1->setDescription('Description factice');
        // $company->setCommercial('3');

        $company2 = new Company();
        $company2->setName('Pharmacie de Rennes');
        $company2->setFirstAdressField('5 rue de pourquoi pas');
        $company2->setsecondAdressField('Second champ vide');
        $company2->setPostalCode('35000');
        $company2->setCity('Rennes');
        $company2->setCountry('France');
        $company2->setDescription('Description factice');

        $company3 = new Company();
        $company3->setName('Pharmacie de Nice');
        $company3->setFirstAdressField('5 rue de pourquoi pas');
        $company3->setsecondAdressField('Second champ vide');
        $company3->setPostalCode('06000');
        $company3->setCity('Nice');
        $company3->setCountry('France');
        $company3->setDescription('Description factice');

        $company4 = new Company();
        $company4->setName('Pharmacie de Toulon');
        $company4->setFirstAdressField('5 rue de pourquoi pas');
        $company4->setsecondAdressField('Second champ vide');
        $company4->setPostalCode('83000');
        $company4->setCity('Toulon');
        $company4->setCountry('France');
        $company4->setDescription('Description factice');

        $manager->persist($company1);
        $manager->persist($company2);
        $manager->persist($company3);
        $manager->persist($company4);
        // $manager->flush();



        // Utilisateurs
        $admin = new User();
        $admin->setEmail('admin@admin.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $encodedPassword = $this->passwordEncoder->encodePassword($admin, 'admin');
        $admin->setPassword($encodedPassword);
        $admin->setFirstname('admin');
        $admin->setLastname('admin');
        $admin->setCompany($company1);

        $user = new User();
        $user->setEmail('user@user.fr');
        $user->setRoles(['ROLE_MEMBER']);
        $encodedPassword = $this->passwordEncoder->encodePassword($user, 'user');
        $user->setPassword($encodedPassword);
        $user->setFirstname('user');
        $user->setLastname('user');
        $user->setCompany($company2);

        $developer = new User();
        $developer->setEmail('developer@developer.fr');
        $developer->setRoles(['ROLE_DEVELOPER']);
        $encodedPassword = $this->passwordEncoder->encodePassword($developer, 'developer');
        $developer->setPassword($encodedPassword);
        $developer->setFirstname('developer');
        $developer->setLastname('developer');
        $developer->setCompany($company3);

        $commercial = new User();
        $commercial->setEmail('commercial@commercial.fr');
        $commercial->setRoles(['ROLE_BUSINESS']);
        $encodedPassword = $this->passwordEncoder->encodePassword($commercial, 'commercial');
        $commercial->setPassword($encodedPassword);
        $commercial->setFirstname('commercial');
        $commercial->setLastname('commercial');
        $commercial->setCompany($company4);

        $manager->persist($admin);
        $manager->persist($user);
        $manager->persist($developer);
        $manager->persist($commercial);


        // Posts
        $post1 = new Post();
        $post1->setTitle('Titre du post 1');
        $post1->setContent('Contenu du post 1');
        $post1->setAuthor($admin);

        $post2 = new Post();
        $post2->setTitle('Titre du post 2');
        $post2->setContent('Contenu du post 2');
        $post2->setAuthor($user);

        $post3 = new Post();
        $post3->setTitle('Titre du post 3');
        $post3->setContent('Contenu du post 3');
        $post3->setAuthor($developer);

        $post4 = new Post();
        $post4->setTitle('Titre du post 4');
        $post4->setContent('Contenu du post 4');
        $post4->setAuthor($commercial);

        $manager->persist($post1);
        $manager->persist($post2);
        $manager->persist($post3);
        $manager->persist($post4);

        $manager->flush();
    }
}
