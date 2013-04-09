<?php

namespace Esolving\Eschool\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;

class ChoiceFatherType extends AbstractType {

    protected $container;
    protected $options;

    public function __construct(Container $container) {
        $this->container = $container;
        $defaults = array(
            'userId' => null,
            'role' => null
        );
        $this->options = $defaults;
    }

    public function setOptions(array $options) {
        $this->options = array_merge($this->options, $options);
    }

    public function getFathers($xuserId = null) {
        $getFathers = $this
                ->container
                ->get("doctrine")
                ->getRepository("EsolvingEschoolUserBundle:Father")
                ->findAllExceptSelf($xuserId);
        ;
        return $getFathers;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $arrayFather = array();
        $user = $this->container->get('doctrine')->getRepository('EsolvingEschoolUserBundle:User')->find($this->options['userId']);
        $arrayRoles = array();
        if ($user) {
            foreach ($user->getRolesAccess() as $rolesAccessV) {
                $arrayRoles[] = $rolesAccessV->getRoleType()->getName();
            }
        }
        if (in_array('ROLE_FATHER', $arrayRoles)) {
            $fathers = $this->getFathers($user->getId());
        } else {
            $fathers = $this->getFathers();
        }
        foreach ($fathers as $fatherV) {
            $arrayFather[$fatherV->getId()] = $fatherV->__toString();
        }
        $builder
                ->add('choiceFathers', 'choice', array(
                    'choices' => $arrayFather,
                    'multiple' => true,
                    'required' => true,
                    'label' => 'fathers',
                    'constraints' => new \Symfony\Component\Validator\Constraints\Count(array('min' => 1, 'max' => 2))
                        )
                )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'translation_domain' => 'EsolvingEschoolUserBundle'
        ));
    }

    public function getName() {
        return 'esolving_eschool_userB_choice_fathers';
    }

}
