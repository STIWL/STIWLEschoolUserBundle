<?php

namespace Esolving\Eschool\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

class StudentInscribeFilterType extends AbstractType {

    private $em;
    private $options;

    public function __construct(EntityManager $em) {
        $this->em = $em;
        $default = array(
            'year' => false
        );
        $this->options = $default;
    }

    public function setOptions(array $options) {
        $this->options = array_merge($this->options, $options);
    }

//    private function getYearsInscribe() {
//        $yearsInscribe = $this->em->getRepository('EsolvingEschoolRoomBundle:StudentInscribe')->findYearsInscribe();
//        return $yearsInscribe;
//    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'translation_domain' => 'EsolvingEschoolUserBundle'
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
//        $yearsInscribe = array();
//        foreach ($this->getYearsInscribe() as $yearsInscribeV) {
//            $yearsInscribe[$yearsInscribeV->getInscribedYearAt()] = $yearsInscribeV->getInscribedYearAt();
//        }
        $builder
                ->add('dateStart', 'date', array(
                    'label' => 'date_start',
                    'required' => true
                ))
                ->add('dateEnd', 'date', array(
                    'label' => 'date_end',
                    'required' => true
                        )
        );
//        if ($this->options['year']) {
//            $builder->add('year', 'choice', array(
//                        'choices' => $yearsInscribe,
//                        'preferred_choices' => array(date('Y')),
//                        'label' => 'year',
//                        'required' => true
//                    ))
//                    ->add('text', 'text', array(
//                        'required' => true,
//                        'constraints' => array(new \Symfony\Component\Validator\Constraints\NotBlank())
//                    ))
//            ;
//        }
    }

    public function getName() {
        return 'esolving_eschool_roomB_Student_inscribe_filter';
    }

}
