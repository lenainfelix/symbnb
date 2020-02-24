<?php

namespace App\DataFixtures;
use App\Entity\Ad;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }  

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Felix')
                    ->setLastName('Lenain')
                    ->setEmail('felixlenain@yahoo.fr')
                    ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                    ->setPicture("https://randomuser.me/api/portraits/men/27.jpg")
                    ->setIntroduction($faker->sentence(4))
                    ->setDescription('<p>'.join('</p><p>',$faker->paragraphs(3)).'</p>')
                    ->addUserRole($adminRole);

                    $manager->persist($adminUser);


        //nous gerons les utilisateurs
        $users=[];
        $genres=["male","female"];

        for ($i=1;$i<15 ; $i++)
        {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName($genres));
            $user->setLastName($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPicture('https://randomuser.me/api/portraits/' . (($genre=='male' ? 'men/' : 'women/'). mt_rand(1,99)).'.jpg');
            $user->setHash($hash);
            $user->setIntroduction($faker->sentence(8));
            $user->setDescription('<p>'.join('</p><p>',$faker->paragraphs(3)).'</p>');
            
            $manager->persist($user);
            $users [] = $user;
        }
        
        //nous gérons les annonces
        for($i = 1; $i<30; $i++)
        {
            $ad =new Ad();
            $title = $faker->sentence(6);
           
            //$coverImage = $faker->imageUrl(1000,350);
            $coverImage = 'https://picsum.photos/id/'.mt_rand(10, 50).'/1920/600';
            $intro = $faker->paragraph(2);
            $content = '<p>'.join('</p><p>',$faker->paragraphs(5)).'</p>';

            $user = $users[mt_rand(0, count($users)-1)];

            $ad->setTitle($title);
            $ad->setPrice(mt_rand(35, 250));
            $ad->setIntroduction($intro);
            $ad->setContent($content);
            $ad->setCoverImage($coverImage);
            $ad->setRooms(mt_rand(1, 4));
            $ad->setAuthor($user);
            
            for($j = 1; $j<= mt_rand(2,5); $j++)
            {
                $image =new Image();
                
                $image->setUrl('https://picsum.photos/id/'.mt_rand(10, 50).'/1920/600');
                $image->setCaption($faker->sentence(3));
                $image->setAd(($ad));
                
                $manager->persist($image);
            }
            $manager->persist($ad);
        }



        $manager->flush();
    }
}
