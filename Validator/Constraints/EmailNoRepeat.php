<?php

namespace Esolving\Eschool\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EmailNoRepeat extends Constraint {

    public $message = 'this_email_is_not_available';
    public $userId = null;
//    public $required = true;

    public function validatedBy() {
        return 'EmailNoRepeatValidator';
    }

}