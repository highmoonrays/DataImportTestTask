<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Product;
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
     * UniqueProductValidator constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed $property
     * @param Constraint $constraint
     * @inheritDoc
     */
    public function validate($property, Constraint $constraint): void
    {
        if (!$constraint instanceof CustomUniqueEntity) {
            throw new UnexpectedTypeException($constraint, CustomUniqueEntity::class);
        }

        if (null === $property || '' === $property) {
            return;
        }

        if (!is_object($property)) {
            throw new UnexpectedValueException($property, 'object');
        }

        if ($this->em->getRepository(Product::class)->findOneByCode($property->getCode())) {
            $this->context->buildViolation($constraint->message)
                ->atPath("code")
                ->addViolation();
        }
    }
}
