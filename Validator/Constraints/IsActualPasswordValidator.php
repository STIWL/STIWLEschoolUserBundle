<?php

namespace Esolving\Eschool\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class IsActualPasswordValidator extends ConstraintValidator {

    private $em;
    private $container;

    public function __construct(EntityManager $em, Container $container) {
        $this->em = $em;
        $this->container = $container;
    }

    public function validate($value, Constraint $constraint) {
        $getTokenUser = $this->container->get('security.context')->getToken()->getUser();
//        $id = $getTokenUser->getId();
        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($getTokenUser);
        $valueEncoder = $encoder->encodePassword($value, $getTokenUser->getSalt());
        if($constraint->required){
            $required= true;
        } else {
            $required = $value ? true:false;
        }
        if ($valueEncoder != $getTokenUser->getPassword() && $required) {
            $this->context->addViolation($constraint->message, array(), null);
        }
    }

}