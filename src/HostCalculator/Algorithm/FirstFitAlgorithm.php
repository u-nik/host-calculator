<?php

namespace App\HostCalculator\Algorithm;

use App\HostCalculator\Specification\SpecificationAwareHostInterface;

class FirstFitAlgorithm implements AlgorithmInterface
{
    public function add(SpecificationAwareHostInterface $vm, array &$hosts): bool
    {
        return array_any($hosts, fn($host) => $host->addVM($vm));
    }
}
