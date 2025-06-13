<?php

namespace Testing\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Model\EFoto;
use Model\EUfficio;
use Smknstd\FakerPicsumImages\FakerPicsumImagesProvider;

class EFotoFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('it_IT');
        $faker->addProvider(new FakerPicsumImagesProvider($faker));

        for ($i = 0; $i < 20; $i++) {
            $ufficio = $this->getReference("EUfficio_" . $i, EUfficio::class);
            for ($j = 0; $j < 3; $j++) {
                $url = $faker->imageUrl(width: 800, height: 600);

                $content = file_get_contents($url);
                // Hack per ottenere la dimensione del file e content-type
                $headers = get_headers($url);
                $size = $headers['Content-Length'] ?? strlen($content);
                $mime_type = $headers['Content-Type'] ?? "image/jpeg";
                $foto = new EFoto();
                $foto->setUfficio($ufficio)
                    ->setContent($content)
                    ->setMimeType($mime_type)
                    ->setSize($size);
                $manager->persist($foto);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            EUfficioFixture::class,
        );
    }
}
