<?php

declare(strict_types=1);

namespace App\HostCalculator\Specification;

class Specification
{
    /**
     * @param int $cpuCores
     * @param int $memory
     * @param int $bandwidth
     */
    public function __construct(protected int $cpuCores, protected int $memory, protected int $bandwidth)
    {
    }

    /**
     * Checks if the current specification can accommodate another specification.
     *
     * @param Specification $specification
     * @return bool
     */
    public function fitsIn(Specification $specification): bool
    {
        return $this->cpuCores <= $specification->cpuCores &&
            $this->memory <= $specification->memory &&
            $this->bandwidth <= $specification->bandwidth;
    }

    /**
     * Calculates the difference between the current specification and another specification.
     *
     * @param Specification $specification The specification to compare with.
     * @return Specification
     */
    public function diffWith(Specification $specification): Specification
    {
        return new Specification(
            $this->cpuCores - $specification->cpuCores,
            $this->memory - $specification->memory,
            $this->bandwidth - $specification->bandwidth
        );
    }

    public function compareTo(Specification $specification): int
    {
        if ($this->cpuCores < $specification->cpuCores) {
            return -1;
        } elseif ($this->cpuCores > $specification->cpuCores) {
            return 1;
        }

        if ($this->memory < $specification->memory) {
            return -1;
        } elseif ($this->memory > $specification->memory) {
            return 1;
        }

        if ($this->bandwidth < $specification->bandwidth) {
            return -1;
        } elseif ($this->bandwidth > $specification->bandwidth) {
            return 1;
        }

        return 0; // Specifications are equal
    }

    /**
     * Checks if the current specification is exhausted.
     *
     * @return bool
     */
    public function isExhausted(): bool
    {
        return $this->cpuCores < 0 || $this->memory < 0 || $this->bandwidth < 0;
    }
}
