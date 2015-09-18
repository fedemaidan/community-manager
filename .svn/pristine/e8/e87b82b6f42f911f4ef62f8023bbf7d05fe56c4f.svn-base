<?php

namespace CM\ModelBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CM\Bundle\ModelBundle\Entity\Role;
use CM\Bundle\ModelBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $usuario1 = new User();
        $usuario1->setEmail('admin@email.com');
        $usuario1->setPassword('admin.');
        $usuario1->setNombre('AdminNombre');
        $usuario1->setApellido('AdminApellido');
        $manager->persist($usuario1);

        $usuario2 = new User();
        $usuario2->setEmail('moderator@email.com');
        $usuario2->setPassword('moderator.');
        $usuario2->setNombre('ModeratorNombre');
        $usuario2->setApellido('ModeratorApellido');
        $manager->persist($usuario2);

        $usuario3 = new User();
        $usuario3->setEmail('adminpage@email.com');
        $usuario3->setPassword('adminpage.');
        $usuario3->setNombre('AdminPageNombre');
        $usuario3->setApellido('AdminPageApellido');
        $manager->persist($usuario3);

        $usuario4 = new User();
        $usuario4->setEmail('audience@email.com');
        $usuario4->setPassword('audience.');
        $usuario4->setNombre('AudienceNombre');
        $usuario4->setApellido('AudienceApellido');
        $manager->persist($usuario4);

        $rol_admin = new Role();
        $rol_admin->setName('administrator');
        $rol_admin->setRole('ROLE_ADMIN');
        $manager->persist($rol_admin);

        $rol_moderator = new Role();
        $rol_moderator->setName('moderator');
        $rol_moderator->setRole('ROLE_MODEARATOR');
        $manager->persist($rol_moderator);

        $rol_admin_page = new Role();
        $rol_admin_page->setName('admin page');
        $rol_admin_page->setRole('ROLE_ADMIN_PAGE');
        $manager->persist($rol_admin_page);

        $rol_audience = new Role();
        $rol_audience->setName('audience generator');
        $rol_audience->setRole('ROLE_AUDIENCE_GENERATOR');
        $manager->persist($rol_audience);

        $usuario1->addRole($rol_admin);
        $rol_admin->addUsers($usuario1);

        $usuario2->addRole($rol_moderator);
        $rol_moderator->addUsers($usuario2);

        $usuario3->addRole($rol_admin_page);
        $rol_admin_page->addUsers($usuario3);

        $usuario4->addRole($rol_audience);
        $rol_audience->addUsers($usuario4);

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