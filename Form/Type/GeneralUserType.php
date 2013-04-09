<?php

namespace Esolving\Eschool\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Esolving\Eschool\UserBundle\Validator\Constraints\IsFather;
use Esolving\Eschool\UserBundle\Validator\Constraints\IsStudent;
use Esolving\Eschool\UserBundle\Validator\Constraints\IsTeacher;
use Esolving\Eschool\UserBundle\Validator\Constraints\IsStudentRole;
use Symfony\Component\Validator\Constraints\Count;
use Esolving\Eschool\UserBundle\Validator\Constraints\EmailNoRepeat;

class GeneralUserType extends AbstractType {

    private $container;
    private $em;
    private $options;

    public function __construct(Container $container, EntityManager $em) {
        $this->container = $container;
        $this->em = $em;
        $defaults = array(
            'userId' => null,
            'role' => null,
            'roles' => null
        );
        $this->options = $defaults;
    }

    public function setOptions(array $options) {
        $this->options = array_merge($this->options, $options);
    }

    public function getTypeByCategoryByLanguage($xcategory, $xlanguage) {
        return $getSex = $this
                ->em
                ->getRepository("EsolvingEschoolCoreBundle:Type")
                ->findByCategoryByLanguage($xcategory, $xlanguage);
        ;
    }

    public function getRoleTeacherByLanguage($xlanguage) {
        $teacher = $this->em->getRepository('EsolvingEschoolUserBundle:Role')->findByRoleByLanguage('ROLE_TEACHER', $xlanguage);
        return $teacher;
    }

    public function getRoleStudentByLanguage($xlanguage) {
        $student = $this->em->getRepository('EsolvingEschoolUserBundle:Role')->findByRoleByLanguage('ROLE_STUDENT', $xlanguage);
        return $student;
    }

    public function getRoleFatherByLanguage($xlanguage) {
        $father = $this->em->getRepository('EsolvingEschoolUserBundle:Role')->findByRoleByLanguage('ROLE_FATHER', $xlanguage);
        return $father;
    }

    public function getAllRoleByLanguageExceptAdmin($xlanguage) {
        $roles = $this->em->getRepository('EsolvingEschoolUserBundle:Role')->findAllRoleByLanguageExceptAdmin('ROLE_ADMIN', $this->container->get('request')->getLocale());
        return $roles;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'translation_domain' => 'EsolvingEschoolUserBundle',
            'data_class' => 'Esolving\Eschool\UserBundle\Entity\User',
            'roles' => null,
            'user' => null
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('rolesAccess', null, array(
            'constraints' => array(new IsFather(array('userId' => $this->options['userId'])), new IsStudent(array('userId' => $this->options['userId'])), new IsTeacher(array('userId' => $this->options['userId'])), new IsStudentRole()),
            'group_by' => 'rolesAccess',
            'choices' => $this->getAllRoleByLanguageExceptAdmin($this->container->get('request')->getLocale()),
            'property' => 'roleType.languages.values[0].description',
            'label' => 'roles',
                )
        );
//        if ($this->options['role'] == 'ROLE_STUDENT') {
//            $user = $options['user'];
//            if (in_array('ROLE_FATHER', $user->getRoles())) {
//                $fathers = $this->getFathers($user->getId());
//            } else {
//                $fathers = $this->getFathers();
//            }
//            $builder
//                    ->add('fathers', null, array(
//                        'choices' => $fathers,
//                        'multiple' => true,
//                        'required' => true,
//                        'label' => 'fathers',
//                        'constraints' => new Count(array('min' => 1, 'max' => 2))
//                            )
//                    )
//            ;
//        }
        $user = $options['user'];
        if (count($this->options['roles']) > 0) {
            if (in_array('ROLE_STUDENT', $this->options['roles'])) {
                if (in_array('ROLE_FATHER', $user->getRoles())) {
                    $fathers = $this->getFathers($user->getId());
                } else {
                    $fathers = $this->getFathers();
                }
                $builder
                        ->add('fathers', null, array(
                            'choices' => $fathers,
                            'multiple' => true,
                            'required' => true,
                            'label' => 'fathers',
                            'constraints' => new Count(array('min' => 1, 'max' => 2))
                                )
                        )
                ;
            }
        }
        if (count($options['roles']) > 0) {
            $user->getRolesAccess()->clear();
            foreach ($options['roles'] as $roleAccessV) {
                $user->addRolesAcces($roleAccessV);
            }
        }
        $builder->setData($user);
        $builder
                ->add('name', null, array(
//                    'constraints' => array(new NotBlank(), new MinLength(20)),
                    'label' => 'name'
                        )
                )
                ->add('lastName', null, array(
                    'label' => 'last_name',
//                    'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank(),
                        )
                )
                ->add('address', null, array(
                    'label' => 'address'
                        )
                )
                ->add('dateBorn', null, array(
                    'label' => 'date_born',
                    'years' => range(date('Y') - 60, date('Y') + 10)
                        )
                )
                ->add('sexType', null, array(
                    'group_by' => 'sexType',
                    'choices' => $this->getTypeByCategoryByLanguage("sex", $this->container->get('request')->getLocale()),
                    'property' => 'languages.values[0].description',
                    'required' => true,
                    'label' => 'sex'
                        )
                )
                ->add('distritType', null, array(
                    'group_by' => 'distritType',
                    'choices' => $this->getTypeByCategoryByLanguage("distrit", $this->container->get('request')->getLocale()),
                    'property' => 'languages.values[0].description',
                    'required' => true,
                    'label' => 'distrit'
                        )
                )
                ->add('email', 'email', array(
                    'label' => 'email',
                    'constraints' => new EmailNoRepeat(array('userId' => $this->options['userId']))
                        )
                )
                ->add('groupBlodType', null, array(
                    'group_by' => 'groupBlodType',
                    'choices' => $this->getTypeByCategoryByLanguage("groupblod", $this->container->get('request')->getLocale()),
                    'property' => 'languages.values[0].description',
                    'required' => true,
                    'label' => 'group_blod'
                        )
                )
                ->add('headquarterType', null, array(
                    'group_by' => 'headquarterType',
                    'choices' => $this->getTypeByCategoryByLanguage("headquarter", $this->container->get('request')->getLocale()),
                    'property' => 'languages.values[0].description',
                    'required' => true,
                    'label' => 'headquarter'
                        )
                )
                ->add('phone', null, array(
                    'label' => 'phone'
                        )
                )
                ->add('phoneMovil', null, array(
                    'label' => 'phone_movil'
                        )
                )
                ->add('sectionType', null, array(
                    'group_by' => 'sectionType',
                    'choices' => $this->getTypeByCategoryByLanguage("section", $this->container->get('request')->getLocale()),
                    'property' => 'languages.values[0].description',
                    'required' => true,
                    'label' => 'section'
                        )
                )
                ->add('status', null, array('label' => 'status'))
        ;
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

    public function getName() {
        return 'esolving_eschool_userB_general';
    }

}