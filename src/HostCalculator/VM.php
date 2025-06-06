<?php

declare(strict_types=1);

namespace App\HostCalculator;

use App\HostCalculator\Specification\Specification;
use App\HostCalculator\Specification\SpecificationAwareInterface;

/**
 * Represents a virtual machine (VM) with its resources.
 */
readonly class VM implements SpecificationAwareInterface
{
    /**
     * @param string $id The unique identifier for the VM.
     * @param \App\HostCalculator\Specification\Specification $specification The specification of the VM.
     */
    public function __construct(
        public string $id,
        public Specification $specification,
    ) {
    }

    /**
     * Returns the specification of the VM.
     *
     * @return \App\HostCalculator\Specification\Specification
     */
    public function getSpecification(): Specification
    {
        return $this->specification;
    }

    /**
     * Returns the unique identifier of the VM.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
