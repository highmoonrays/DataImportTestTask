<?php

declare(strict_types=1);

namespace App\Validator;

use App\Service\Tool\ObjectToAssociativeArrayTransform;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class UniqueProductValidator
 * @package App\Validator\Constraint
 * @Annotation
 */
class CustomUniqueEntityValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ObjectToAssociativeArrayTransform
     */
    private $objectToAssociativeArrayTransform;

    /**
     * UniqueProductValidator constructor.
     * @param EntityManagerInterface $em
     * @param ObjectToAssociativeArrayTransform $objectToAssociativeArrayTransform
     */
    public function __construct(
        EntityManagerInterface $em,
        ObjectToAssociativeArrayTransform $objectToAssociativeArrayTransform
    )
    {
        $this->em = $em;
        $this->objectToAssociativeArrayTransform = $objectToAssociativeArrayTransform;
    }

    /**
     * @param mixed $objectToValidate
     * @param Constraint $constraint
     * @inheritDoc
     * @throws \ReflectionException
     */
    public function validate($objectToValidate, Constraint $constraint): void
    {
        if (!$constraint instanceof CustomUniqueEntity) {
            throw new UnexpectedTypeException($constraint, CustomUniqueEntity::class);
        }

        if (null === $objectToValidate || '' === $objectToValidate) {
            return;
        }

        if (!is_object($objectToValidate)) {
            throw new UnexpectedValueException($objectToValidate, 'object');
        }
        $arrayToValidate = $this->objectToAssociativeArrayTransform->transform($objectToValidate);
        $uniqueFields = [];

        foreach ($constraint->fields as $field){
            $uniqueFields[$field] = $arrayToValidate[$field];
        }

        if ($this->em->getRepository($constraint->className)
            ->findAll($uniqueFields)) {
            $this->context->buildViolation($constraint->message)
                ->atPath($constraint->fieldToFireError)
                ->addViolation();
        }
    }
}
