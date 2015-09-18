<?php

namespace CM\ModelBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CM\Bundle\ModelBundle\Entity\Tag;

class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tag = new Tag();
        $tag->setName("sugerencia");
        $manager->persist($tag);;

        $tag2 = new Tag();
        $tag2->setName("apoyo");
        $manager->persist($tag2);

        $tag3 = new Tag();
        $tag3->setName("reclamo gcba");
        $manager->persist($tag3);

        $tag4 = new Tag();
        $tag4->setName("afiliación/participación");
        $manager->persist($tag4);

        $tag5 = new Tag();
        $tag5->setName("fiscalización");
        $manager->persist($tag5);

        $tag6 = new Tag();
        $tag6->setName("ayuda");
        $manager->persist($tag6);

        $tag7 = new Tag();
        $tag7->setName("referente territorial");
        $manager->persist($tag7);

        $tag8 = new Tag();
        $tag8->setName("voluntariado");
        $manager->persist($tag8);

        $tag9 = new Tag();
        $tag9->setName("insulto");
        $manager->persist($tag9);

        $tag10 = new Tag();
        $tag10->setName("pedido de baja");
        $manager->persist($tag10);

        
        $manager->flush();

    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}