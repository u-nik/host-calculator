<?php
declare(strict_types=1);

namespace App\HostCalculator\Specification;

/**
 * Interface for host interfaces that are aware of specifications.
 * This interface is used to ensure that the host has a specification
 * that can be used to check resource constraints.
 */
interface SpecificationAwareHostInterface
{
   public function __construct(string $id, Specification $specification);

   public function getSpecification(): Specification;

   public function getId(): string;
}
