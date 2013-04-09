<?php

namespace Esolving\Eschool\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsStudentRole extends Constraint {

    public $message = 'in_role_student_no_more_roles';

//    public $required = true;

}