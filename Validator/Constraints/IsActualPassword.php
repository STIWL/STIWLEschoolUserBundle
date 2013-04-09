<?php

namespace Esolving\Eschool\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsActualPassword extends Constraint {

    public $message = 'this_value_is_not_actual_password';
    public $required = true;
    
    public function validatedBy() {
        return 'IsActualPasswordValidator';
    }
}