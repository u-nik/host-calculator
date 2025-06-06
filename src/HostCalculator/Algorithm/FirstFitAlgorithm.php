<?php

namespace App\HostCalculator\Algorithm;

use App\HostCalculator\Specification\SpecificationAwareInterface;

class FirstFitAlgorithm implements AlgorithmInterface
{
    public function add(SpecificationAwareInterface $vm, array $hosts): bool
    {
        return array_any($hosts, fn($host) => $host->addVM($vm));
    }
}
