<?php

namespace Esolving\Eschool\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;

class StudentType extends AbstractType {

    private $container;
    private $em;

    public function __construct(Container $container, EntityManager $em) {
        $this->container = $container;
        $this->em = $em;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'translation_domain' => 'EsolvingEschoolUserBundle',
            'data_class' => 'Esolving\Eschool\UserBundle\Entity\Student',
            'cascade_validation' => true
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $formUserGeneral = $this->container->get('esolving_eschool_user.form.type.general');
        $formUserGeneral->setOptions(array('role' => 'ROLE_STUDENT','rolesAccessStudent'=>true));
        $builder
                ->add('fathers', null, array(
                    'label' => 'fathers',
                ))
                ->add('user', $formUserGeneral, array(
                    'label' => 'user',
                ))
        ;
    }

    public function getName() {
        return 'esolving_eschool_userB_student';
    }

}