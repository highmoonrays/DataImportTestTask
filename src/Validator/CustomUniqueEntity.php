<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CustomUniqueEntity extends Constraint
{
    /**
     * @var string
     */
    public $message;

    /**
     * @var mixed
     */
    public $fields;

    /**
     * @return string
     */
    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
