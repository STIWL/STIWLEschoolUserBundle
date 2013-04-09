<?php

namespace Esolving\Eschool\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Esolving\Eschool\UserBundle\Entity\User,
    Esolving\Eschool\UserBundle\Entity\Father,
    Esolving\Eschool\UserBundle\Entity\Student;

class LoadStudentData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface {

    protected $manager;
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {
        $this->manager = $manager;
        $students = array(
            'student_soe' => array(
                'sex' => 'sex_woman',
                'groupblod' => 'groupblod_o-',
                'distrit' => 'distrit_lince',
                'headquarter' => 'headquarter_atte',
                'section' => 'section_secundary',
                'name' => 'Soe',
                'lastname' => 'Sánchez Valdivia',
                'dateborn' => new \DateTime('2000-04-12'),
                'phone' => '3360524',
                'phonemovil' => '998752617',
                'email' => 'luis22989@hotmail.com',
                'address' => 'Av. Morales duarez #2249',
                'code' => 'luis',
                'password' => 'luis',
                'status' => '1',
                'fathers' => array('father_luis', 'father_maricela'),
                'roles' => array(
                    'ROLE_STUDENT'
                )
            ),
            'student_jubal' => array(
                'sex' => 'sex_man',
                'groupblod' => 'groupblod_o-',
                'distrit' => 'distrit_lince',
                'headquarter' => 'headquarter_atte',
                'section' => 'section_secundary',
                'name' => 'Jubal',
                'lastname' => 'Sánchez Valdivia',
                'dateborn' => new \DateTime('2000-03-22'),
                'phone' => '3360524',
                'phonemovil' => '987619234',
                'email' => 'jubal@hotmail.com',
                'address' => 'Av. algo',
                'code' => 'jubal',
                'password' => 'jubal',
                'status' => '1',
                'fathers' => array('father_luis', 'father_maricela'),
                'roles' => array(
                    'ROLE_STUDENT'
                )
            ),
            'student_james' => array(
                'sex' => 'sex_man',
                'groupblod' => 'groupblod_o-',
                'distrit' => 'distrit_lince',
                'headquarter' => 'headquarter_atte',
                'section' => 'section_secundary',
                'name' => 'James',
                'lastname' => 'Gonzales Martinez',
                'dateborn' => new \DateTime('1999-02-02'),
                'phone' => '4435621',
                'phonemovil' => '967761253',
                'email' => 'james@hotmail.com',
                'address' => 'Av. algo',
                'code' => 'jame',
                'password' => 'james',
                'status' => '1',
                'fathers' => array('father_jorge', 'father_lucia'),
                'roles' => array(
                    'ROLE_STUDENT'
                )
            ),
            'student_carla' => array(
                'sex' => 'sex_woman',
                'groupblod' => 'groupblod_o-',
                'distrit' => 'distrit_lince',
                'headquarter' => 'headquarter_atte',
                'section' => 'section_secundary',
                'name' => 'Carla',
                'lastname' => 'Gonzales Martinez',
                'dateborn' => new \DateTime('1999-02-02'),
                'phone' => '4435621',
                'phonemovil' => '987622435',
                'email' => 'carla@hotmail.com',
                'address' => 'Av. algo',
                'code' => 'carla',
                'password' => 'carla',
                'status' => '1',
                'fathers' => array('father_jorge', 'father_lucia'),
                'roles' => array(
                    'ROLE_STUDENT'
                )
            )
        );

        foreach ($students as $studentK => $property) {
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

            $student = new Student();
            $student->setUser($user);
            $manager->persist($student);
            if (is_array($property['fathers'])) {
                foreach ($property['fathers'] as $fatherV) {
                    $father = $manager->merge($this->getReference($fatherV));
                    $student->getFathers()->add($father);
                    $father->getStudents()->add($student);
                }
            }
            $this->addReference($studentK, $student);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 4;
    }

}

