<?php

namespace Esolving\Eschool\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Esolving\Eschool\UserBundle\Entity\User,
    Esolving\Eschool\UserBundle\Entity\Father;

class LoadFatherData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    protected $manager;
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {
        $this->manager = $manager;
        $fathers = array(
            'father_maricela' => array(
                'sex' => 'sex_woman',
                'groupblod' => 'groupblod_o+',
                'distrit' => 'distrit_lince',
                'headquarter' => 'headquarter_atte',
                'section' => 'section_secundary',
                'name' => 'Maricela Iveth',
                'lastname' => 'Valdivia Reyna',
                'dateborn' => new \DateTime('1978-07-03'),
                'phone' => '2550872',
                'phonemovil' => '987164213',
                'email' => 'mary_4ever6@hotmail.com',
                'address' => 'Av. algo',
                'code' => 'maricela',
                'password' => 'maricela',
                'status' => '1',
                'roles' => array(
                    'ROLE_FATHER', 'ROLE_TEACHER'
                )
            ),
            'father_luis' => array(
                'sex' => 'sex_man',
                'groupblod' => 'groupblod_o+',
                'distrit' => 'distrit_lince',
                'headquarter' => 'headquarter_atte',
                'section' => 'section_secundary',
                'name' => 'Luis Alberto',
                'lastname' => 'Sánchez Saldaña',
                'dateborn' => new \DateTime('1979-09-22'),
                'phone' => '3360524',
                'phonemovil' => '9921921622',
                'email' => 'luis22989@hotmail.com',
                'address' => 'Av. Algo',
                'code' => 'luis',
                'password' => 'luis',
                'status' => '1',
                'roles' => array(
                    'ROLE_FATHER','ROLE_SYSTEM'
                )
            ),
            'father_jorge' => array(
                'sex' => 'sex_man',
                'groupblod' => 'groupblod_o-',
                'distrit' => 'distrit_lince',
                'headquarter' => 'headquarter_atte',
                'section' => 'section_secundary',
                'name' => 'Jorge ',
                'lastname' => 'Gonzales Tapia',
                'dateborn' => new \DateTime('1979-01-12'),
                'phone' => '4435621',
                'phonemovil' => '988872615',
                'email' => 'jorge_gonzales@hotmail.com',
                'address' => 'Av. Algo',
                'code' => 'jorge',
                'password' => 'jorge',
                'status' => '1',
                'roles' => array(
                    'ROLE_FATHER'
                )
            ),
            'father_lucia' => array(
                'sex' => 'sex_man',
                'groupblod' => 'groupblod_o+',
                'distrit' => 'distrit_lince',
                'headquarter' => 'headquarter_atte',
                'section' => 'section_secundary',
                'name' => 'Lucia',
                'lastname' => 'Martinez Lopez',
                'dateborn' => new \DateTime('1975-03-25'),
                'phone' => '4435621',
                'phonemovil' => '966748221',
                'email' => 'lucia_martinez@hotmail.com',
                'address' => 'Av. Algo',
                'code' => 'lucia',
                'password' => 'lucia',
                'status' => '1',
                'roles' => array(
                    'ROLE_FATHER'
                )
            )
        );

        foreach ($fathers as $fatherK => $property) {
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
            $father = new Father();
            $father->setUser($user);
            $manager->persist($father);
            $this->addReference($fatherK, $father);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 3;
    }

}