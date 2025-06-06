<?php

namespace App\HostCalculator\Algorithm;

use App\HostCalculator\Host;
use App\HostCalculator\Specification\SpecificationAwareInterface;

interface AlgorithmInterface
{
    public function add(SpecificationAwareInterface $vm, array $hosts): bool;
}
