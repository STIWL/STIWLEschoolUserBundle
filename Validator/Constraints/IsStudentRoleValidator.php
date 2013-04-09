<?php

namespace Esolving\Eschool\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Esolving\Eschool\UserBundle\Entity\Role;
use Symfony\Component\Validator\Exception\ValidatorException;

class IsStudentRoleValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint) {
        $continue = true;
        if (count($value) > 0) {
            if ($value[0] instanceof Role) {
                foreach ($value as $valueV) {
                    if ($valueV->getRoleType()->getName() == 'ROLE_STUDENT' && count($value) > 1) {
                        $continue = false;
                        break;
                    }
                }
            } else {
                throw new ValidatorException('This have to be a ' . get_class(new Role) . ' class');
            }
        }
        if (!$continue) {
            return $this->context->addViolation($constraint->message);
        }
    }

}