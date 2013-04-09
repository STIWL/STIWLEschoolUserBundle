<?php

namespace Esolving\Eschool\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
//use Symfony\Component\Validator\Exception\ValidatorException;

class IsFatherValidator extends ConstraintValidator {

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint) {
        $rolesAccess = $value;
        $rolesArr = array();
        foreach ($rolesAccess as $rolesAccessV) {
            $rolesArr[] = $rolesAccessV->getRoleType()->getName();
        }
        if (!in_array('ROLE_FATHER', $rolesArr)) {
            $father = $this->em->getRepository('EsolvingEschoolUserBundle:Father')->findOneBy(array('user' => $constraint->userId));
            if ($father) {
                if (count($father->getStudents()) > 0) {
                    return $this->context->addViolation($constraint->message);
                }
            }
        }
    }

}