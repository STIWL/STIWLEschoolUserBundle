<?php

namespace Esolving\Eschool\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class IsTeacherValidator extends ConstraintValidator {

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
        if (!in_array('ROLE_TEACHER', $rolesArr)) {
            $teacher = $this->em->getRepository('EsolvingEschoolUserBundle:Teacher')->findOneBy(array('user' => $constraint->userId));
            if ($teacher) {
                if (count($teacher->getSchedules()) > 0) {
                    return $this->context->addViolation($constraint->message);
                }
            }
        }
    }

}