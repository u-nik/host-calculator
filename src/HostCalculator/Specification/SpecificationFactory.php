<?php

declare(strict_types=1);

namespace App\HostCalculator\Specification;

class SpecificationFactory
{
    /**
     * Creates a Specification instance with the given CPU cores, memory, and bandwidth.
     *
     * @param int $value The value for CPU cores, memory, and bandwidth.
     * @return Specification
     */
    public static function createWithAll(int $value): Specification
    {
        return new Specification(
            cpuCores: $value,
            memory: $value,
            bandwidth: $value
        );
    }

    /**
     * Creates a Specification instance with the given CPU cores, memory, and bandwidth.
     *
     * @param int $cpuCores The number of CPU cores.
     * @param int $memory The amount of memory in MB.
     * @param int $bandwidth The bandwidth in Mbps.
     * @return Specification
     */
    public static function createWith($cpuCores, int $memory, int $bandwidth): Specification
    {
        return new Specification(
            cpuCores: $cpuCores,
            memory: $memory,
            bandwidth: $bandwidth
        );
    }
}
