<?php

declare(strict_types=1);

namespace App\Service\ImportTools;

use Doctrine\ORM\EntityManagerInterface;

interface ProductCreatorInterface
{
    public function __construct(EntityManagerInterface $em);

    public function save(array $somethingToSave);
}
