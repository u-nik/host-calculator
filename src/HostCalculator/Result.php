<?php

declare(strict_types=1);

namespace App\HostCalculator;

/**
 * Represents the result of the host calculation.
 */
class Result
{

    /**
     * The number of hosts in the result.
     *
     * @return int
     */
    public int $hostCount {
        get {
            return count($this->hosts);
        }
    }

    /**
     * @param array<Host> $hosts the list of hosts that can accommodate the VMs.
     */
    public function __construct(
        public array $hosts,
    ) {
    }

    /**
     * Return the distribution of VMs across the hosts.
     *
     * @return array<string, array<string>>
     */
    public function getVmDistribution(): array
    {
        $distribution = [];
        foreach ($this->hosts as $host) {
            $distribution[$host->id] = $host->getVMIds();
        }

        return $distribution;
    }
}
