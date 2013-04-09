<?php

namespace Esolving\Eschool\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ValidatorException;

class AreFathersRequiredValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint) {
        $rolesArr = array();
        if (count($value->getRolesAccess()) > 0) {
            foreach ($value->getRolesAccess() as $roleAccessV) {
                $rolesArr[] = $roleAccessV->getRoleType()->getName();
            }
        } 
        if (in_array('ROLE_STUDENT', $rolesArr)) {
            if (count($value->getFathers()) <= 0) {
                return $this->context->addViolation($constraint->message);
            }
        }
    }

}

?>
