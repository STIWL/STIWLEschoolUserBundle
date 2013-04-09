<?php

namespace Esolving\Eschool\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;

class StudentInscribeType extends AbstractType {

    private $container;
    private $em;
    private $options;

    public function __construct(Container $container, EntityManager $em) {
        $this->container = $container;
        $this->em = $em;
        $defaults = array(
            'studentId' => null,
        );
        $this->options = $defaults;
    }

    public function setOptions(array $options) {
        $this->options = array_merge($this->options, $options);
    }

    public function findAllStudentNoInscribe($studentId = null) {
        $students = $this->em->getRepository('EsolvingEschoolUserBundle:Student')->findAllNoInscribe($studentId);
        return $students;
    }

    public function getAllBySectionIdByHeadquarterIdByLanguage($sectionId, $headquarterId, $language) {
        $rooms = $this->em->getRepository('EsolvingEschoolRoomBundle:Room')->findAllBySectionIdByHeadquarterIdByLanguage($sectionId, $headquarterId, $language);
        return $rooms;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $coreService = $this->container->get('esolving_eschool_core');
        $builder
                ->add('student', null, array(
                    'label' => 'student',
                    'choices' => $this->findAllStudentNoInscribe($this->options['studentId']),
                    'property' => 'user.fullName'
                ))
                ->add('room', null, array(
                    'label' => 'room',
                    'choices' => $this->getAllBySectionIdByHeadquarterIdByLanguage($coreService->getSectionId(), $coreService->getHeadquarterId(), $this->container->get('request')->getLocale()),
                    'property' => 'roomType.languages.values[0].description'
                ))
                ->add('status', null, array('label' => 'status'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Esolving\Eschool\RoomBundle\Entity\StudentInscribe',
            'translation_domain' => 'EsolvingEschoolUserBundle'
        ));
    }

    public function getName() {
        return 'esolving_bundle_eschool_userbundle_student_inscribe';
    }

}
