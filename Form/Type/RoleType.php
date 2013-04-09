<?php

namespace Esolving\Eschool\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;

class RoleType extends AbstractType {

    protected $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }

    public function getTypeByCategoryByLanguage($xcategory, $xlanguage) {
        return $getSex = $this
                ->container
                ->get("doctrine")
                ->getRepository("EsolvingEschoolCoreBundle:Type")
                ->findByCategoryByLanguage($xcategory, $xlanguage);
        ;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('roleType', null, array(
                    'group_by' => 'roleType',
                    'choices' => $this->getTypeByCategoryByLanguage("levelaccess", $this->container->get('request')->getLocale()),
//                    'multiple' => true,
                    'property' => 'languages.values[0].description',
                    'required' => true,
                    'label' => 'role'
                        )
                )
//            ->add('user')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Esolving\Eschool\UserBundle\Entity\Role'
        ));
    }

    public function getName() {
        return 'esolving_eschool_userB_role';
    }

}
