<?php

namespace App\HostCalculator\Algorithm;

use App\HostCalculator\Specification\SpecificationAwareInterface;

class BestFitAlgorithm implements AlgorithmInterface
{

    /**
     * Adds a VM to the best fitting host based on the remaining resources.
     *
     * @param SpecificationAwareInterface $vm The VM to be added.
     * @param array<\App\HostCalculator\Host> $hosts The list of available hosts.
     * @return bool True if the VM was added to a host, false otherwise.
     * @throws \App\HostCalculator\VMResourceException
     */
    public function add(SpecificationAwareInterface $vm, array $hosts): bool
    {
        $bestHost = null;
        $bestFit  = null;

        foreach ($hosts as $host) {
            $remaining = $host->getRemaining()->diffWith($vm->getSpecification());
            if (!$remaining->isExhausted()) {
                if ($bestFit === null || $remaining->compareTo($bestFit) === -1) {
                    $bestFit  = $remaining;
                    $bestHost = $host;
                }
            }
        }

        if ($bestHost !== null) {
            $bestHost->addVM($vm);

            return true;
        }

        return false;
    }
}
