<?php

namespace Esolving\Eschool\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AreFathersRequired extends Constraint {

    public $message = 'fathers_required';

    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }

}

?>
