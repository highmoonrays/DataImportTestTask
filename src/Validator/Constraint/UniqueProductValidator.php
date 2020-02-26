<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class UniqueProductCodeValidator
 * @package App\Validator\Constraint
 * @Annotation
 */
class UniqueProductValidator extends ConstraintValidator
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
        if (!$constraint instanceof UniqueProductValidator) {
            throw new UnexpectedTypeException($constraint, UniqueProductValidator::class);
        }

        if (null === $property || '' === $property) {
            return;
        }

        if (!is_string($property)) {
            throw new UnexpectedValueException($property, 'string');
        }

        if ($this->em->getRepository(Product::class)->findOneBySomeField($property)) {
            $this->context->buildViolation($constraint->message)
                ->atPath("$property")
                ->addViolation();
        }
    }
}