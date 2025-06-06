<?php

declare(strict_types=1);

namespace App\HostCalculator;

use App\HostCalculator\Algorithm\AlgorithmInterface;
use App\HostCalculator\Specification\Specification;

class Calculator
{

    public function __construct(
        protected Specification $hostSpecs,
        protected AlgorithmInterface $algorithm
    ) {
    }

    /**
     * Calculates the number of hosts required to run the given VMs.
     *
     * @param \App\HostCalculator\Specification\SpecificationAwareInterface[] $vms
     * @return Result
     * @throws \App\HostCalculator\VMResourceException
     */
    public function calculateHosts(array $vms): Result
    {
        /** @var \App\HostCalculator\Host[] $hosts */
        $hosts = [];

        // First Fit Strategy:
        // Iteriere über die vorhandenen Hosts und prüfe, ob sie die VMs aufnehmen können.
        // Wenn nicht, erstelle einen neuen Host und füge die VM hinzu.
        foreach ($vms as $vm) {
            if (!$this->algorithm->add(vm: $vm, hosts: $hosts)) {
                // If we reach here, it means no existing host could accommodate the VM
                $newHost = new Host('host-'.(count($hosts) + 1), $this->hostSpecs);
                $newHost->addVM($vm);

                $hosts[] = $newHost;
            }
        }

        return new Result($hosts);
    }
}
