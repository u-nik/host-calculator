<?php

namespace App\HostCalculator\Algorithm;

use App\HostCalculator\Host;
use App\HostCalculator\Specification\SpecificationAwareHostInterface;

interface AlgorithmInterface
{
    public function add(SpecificationAwareHostInterface $vm, array &$hosts): bool;
}
