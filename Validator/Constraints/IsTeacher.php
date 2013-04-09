<?php

namespace Esolving\Eschool\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsTeacher extends Constraint {

    public $message = 'is_teacher_and_have_schedule';
    public $userId = null;
//    public $required = true;

    public function validatedBy() {
        return 'IsTeacherValidator';
    }

}