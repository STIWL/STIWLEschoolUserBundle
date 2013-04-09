<?php

namespace Esolving\Eschool\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Esolving\Eschool\DisplayBundle\Validator\Constraints\EqualsFields;
use Esolving\Eschool\UserBundle\Validator\Constraints\IsActualPassword;
use Esolving\Eschool\CoreBundle\Repository\TypeRepository;
use Symfony\Component\DependencyInjection\Container;

class UserUpdateType extends AbstractType {
    
    private $container;
    
    public function __construct(Container $container){
        $this->container = $container;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'translation_domain' => 'EsolvingEschoolUserBundle',
            'data_class' => 'Esolving\Eschool\UserBundle\Entity\User'
        ));
    }

    public function getSex() {
        return $getSex = $this
                ->container
                ->get("doctrine")
                ->getRepository("EsolvingEschoolCoreBundle:Type")
                ->findByCategoryByLanguage("sex", $this->container->get('request')->getLocale());
        ;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('actualPassword', 'password', array(
                    'mapped' => false,
                    'validation_constraint' => array(new IsActualPassword(array('required' => false))),
                    'required' => false,
                    'label' => 'actual_password'
                        )
                )
//                ->add('newPassword', 'password', array(
//                    'mapped' => false,
//                    'required' => false,
//                    'label' => 'new_password'
//                        )
//                )
//                ->add('confirmationPassword', 'password', array(
//                    'validation_constraint' => array(new EqualsFields(array("field" => "newPassword", 'label' => 'new_password', 'required' => false))),
//                    'required' => false,
//                    'mapped' => false,
//                    'label' => 'confirmation_password'
//                        )
//                )
                ->add('email', null, array(
                    'label' => 'email'
                        )
                )
                ->add('passwordRepeated', 'repeated', array(
                    'mapped' => false,
                    'type' => 'password',
                    'invalid_message' => 'the_password_and_new_password_fields_must_be_match',
                    'options' => array('attr' => array('class' => 'password-field')),
                    'required' => false,
                    'second_options' => array('label' => 'confirmation_password'),
                    'first_options' => array('label' => 'new_password'),
                ))
//                ->add('integer', 'integer', array(
//                    'mapped' => false,
//                    'label' => 'integer',
//                    'required' => true
//                        )
//                )
//                ->add('sexType', null, array(
//                    'label' => 'sex'
//                ))
//                ->add('sexType', null, array(
////                    'class' => 'EsolvingEschoolCoreBundle:Type',
//                    'group_by' => 'sexType',
//                    'query_builder' => function(TypeRepository $typeRepo) {
////                        return $typeRepo->createQueryBuilder('type')
////                                ->orderBy('type.name', 'DESC');
//                        $qb = $typeRepo->createQueryBuilder('type');
//                        $q = $qb
//                                ->join("type.languages", "languages")
//                                ->where($qb->expr()->eq("type.category", ":category"))
//                                ->andWhere($qb->expr()->neq("type.name", ':name'))
//                                ->andWhere($qb->expr()->eq("languages.language", ":language"))
//                                ->andWhere($qb->expr()->eq("type.status", "1"))
//                                ->setParameter('category', 'sex')
//                                ->setParameter('name', 'sex')
//                                ->setParameter('language', 'es');
//                        return $q;
//                    },
//                    'property' => 'languages.values[0].description',
//                    'required' => true,
//                    'label' => 'sex'
//                ))
                ->add('sexType', null, array(
                        'group_by' => 'sexType',
                        'choices' => $this->getSex(),
                        'property' => 'languages.values[0].description',
                        'required' => true,
                        'label' => 'sex'
                    ))
        ;
    }

    public function getName() {
        return 'esolving_eschool_userB_update_profile';
    }

}