<?php

namespace Esolving\Eschool\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsFather extends Constraint {

    public $message = 'is_father_and_have_sons_cant_unselect_role_father';
    public $userId = null;
//    public $required = true;

    public function validatedBy() {
        return 'IsFatherValidator';
    }

}