<?php

namespace App\DataFixtures;
use App\Entity\Ad;
use App\Entity\Image;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        
        for($i = 1; $i<30; $i++)
        {
            $ad =new Ad();
            $title = $faker->sentence(6);
           
            //$coverImage = $faker->imageUrl(1000,350);
            $coverImage = 'https://picsum.photos/id/'.mt_rand(10, 50).'/1920/600';
            $intro = $faker->paragraph(2);
            $content = '<p>'.join('</p><p>',$faker->paragraphs(5)).'</p>';

            $ad->setTitle($title);
            $ad->setPrice(mt_rand(35, 250));
            $ad->setIntroduction($intro);
            $ad->setContent($content);
            $ad->setCoverImage($coverImage);
            $ad->setRooms(mt_rand(1, 4));
            
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
