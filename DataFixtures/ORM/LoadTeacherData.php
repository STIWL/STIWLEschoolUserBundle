<?php

namespace Esolving\Eschool\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Esolving\Eschool\UserBundle\Entity\User,
    Esolving\Eschool\UserBundle\Entity\Teacher;

class LoadTeacherData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    protected $manager;
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {
        $this->manager = $manager;
        $teachers = array(
            'teacher_juan' => array(
                'sex' => 'sex_man',
                'groupblod' => 'groupblod_o-',
                'distrit' => 'distrit_lince',
                'headquarter' => 'headquarter_atte',
                'section' => 'section_secundary',
                'name' => 'Juan',
                'lastname' => 'Tenorio Selgado',
                'dateborn' => new \DateTime('1989-09-22'),
                'phone' => '2344553',
                'phonemovil' => '9992187721',
                'email' => 'juan@hotmail.com',
                'address' => 'Av. Algo #algo',
                'code' => 'juan',
                'password' => 'juan',
                'status' => '1',
                'roles' => array(
                    'ROLE_TEACHER'
                )
            )
        );

        foreach ($teachers as $teacherK => $property) {
            $user = new User();
            foreach ($property['roles'] as $role) {
                $user->addRolesacces($manager->merge($this->getReference($role)));
            }

            $user->setSexType($manager->merge($this->getReference($property['sex'])));

            $user->setGroupblodType($manager->merge($this->getReference($property['groupblod'])));

            $user->setDistritType($manager->merge($this->getReference($property['distrit'])));

            $user->setHeadquarterType($manager->merge($this->getReference($property['headquarter'])));

            $user->setSectionType($manager->merge($this->getReference($property['section'])));

            $user->setName($property['name']);
            $user->setLastname($property['lastname']);
            $user->setDateborn($property['dateborn']);
            $user->setPhone($property['phone']);
            $user->setPhonemovil($property['phonemovil']);
            $user->setEmail($property['email']);
            $user->setAddress($property['address']);
            $user->setCode($property['code']);
            $user->setSalt(md5(uniqid()));

            $encoder = $this->container
                    ->get('security.encoder_factory')
                    ->getEncoder($user)
            ;
            $user->setPassword($encoder->encodePassword($property["password"], $user->getSalt()));
            $user->setStatus($property['status']);
            $manager->persist($user);
            $teacher = new Teacher();
            $teacher->setUser($user);
            $manager->persist($teacher);
            $this->addReference($teacherK, $teacher);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 3;
    }

}
