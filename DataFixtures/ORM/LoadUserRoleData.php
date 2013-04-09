<?php

namespace Esolving\Eschool\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Esolving\Eschool\UserBundle\Entity\User,
    Esolving\Eschool\UserBundle\Entity\Role,
    Esolving\Eschool\UserBundle\Entity\Father;

class LoadUserRoleData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    protected $manager;
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {
        $this->manager = $manager;
        $roles = array(
            'ROLE_ADMIN' => array(
                'role_type_id' => 'role_ROLE_ADMIN',
                'status' => '1'
            ),
            'ROLE_TEACHER' => array(
                'role_type_id' => 'role_ROLE_TEACHER',
                'status' => '1'
            ),
            'ROLE_FATHER' => array(
                'role_type_id' => 'role_ROLE_FATHER',
                'status' => '1'
            ),
            'ROLE_STUDENT' => array(
                'role_type_id' => 'role_ROLE_STUDENT',
                'status' => '1'
            ),
            'ROLE_ACADEMIC' => array(
                'role_type_id' => 'role_ROLE_ACADEMIC',
                'status' => '1'
            ),
            'ROLE_TREASURY' => array(
                'role_type_id' => 'role_ROLE_TREASURY',
                'status' => '1'
            ),
            'ROLE_SYSTEM' => array(
                'role_type_id' => 'role_ROLE_SYSTEM',
                'status' => '1'
            )
        );

        foreach ($roles as $roleK => $roleV) {
            $role = new Role();
            $role->setRoleType($manager->merge($this->getReference($roleV['role_type_id'])));
            $role->setStatus($roleV['status']);
            $manager->persist($role);
            $this->addReference($roleK, $role);
        }
        $manager->flush();

        $users = array(
            'pepe' => array(
                'sex' => 'sex_man',
                'groupblod' => 'groupblod_o+',
                'distrit' => 'distrit_lince',
                'headquarter' => 'headquarter_atte',
                'section' => 'section_secundary',
                'name' => 'Pepe Alonso',
                'lastname' => 'Quiroga',
                'dateborn' => new \DateTime('1986-11-14'),
                'phone' => '3323421',
                'phonemovil' => '9934525234',
                'email' => 'pepe@hotmail.com',
                'address' => 'Av. Algo',
                'code' => 'admin',
                'password' => 'admin',
                'status' => '1',
                'roles' => array(
                    'ROLE_ADMIN'
                )
            )
        );

        foreach ($users as $userK => $property) {
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
            $this->addReference($userK, $user);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 2;
    }

}
