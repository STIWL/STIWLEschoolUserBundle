<?php

namespace Esolving\Eschool\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Exception\ValidatorException;

class EmailNoRepeatValidator extends ConstraintValidator {

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint) {
        $email = $value;
        $user = $this->em->getRepository('EsolvingEschoolUserBundle:User')->findOneByEmailExceptSelf($email, $constraint->userId);
        if ($user) {
            return $this->context->addViolation($constraint->message);
        }
    }

}