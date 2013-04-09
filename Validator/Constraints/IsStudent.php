<?php

namespace Esolving\Eschool\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsStudent extends Constraint {

    public $message = 'is_student_and_is_inscribed_in_room';
    public $userId = null;
//    public $required = true;

    public function validatedBy() {
        return 'IsStudentValidator';
    }

}