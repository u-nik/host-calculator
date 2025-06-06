<?php

declare(strict_types=1);

namespace Tests\HostCalculator\Algorithm;

use App\HostCalculator\Host;
use App\HostCalculator\Specification\Specification;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use App\HostCalculator\Algorithm\BestFitAlgorithm;
use App\HostCalculator\Specification\SpecificationAwareInterface;

#[CoversClass(BestFitAlgorithm::class)]
class BestFitAlgorithmTest extends TestCase
{
    public function testAddAssignsVmToBestFittingHost()
    {
        $vm = $this->createMock(SpecificationAwareInterface::class);
        $vmSpec = $this->createMock(Specification::class);
        $vm->method('getSpecification')->willReturn($vmSpec);

        $host1 = $this->getMockBuilder(Host::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getRemaining', 'addVM'])
            ->getMock();
        $host2 = $this->getMockBuilder(Host::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getRemaining', 'addVM'])
            ->getMock();

        $spec1 = $this->createMock(Specification::class);
        $spec2 = $this->createMock(Specification::class);

        // Host 1: diffWith returns $diff1, Host 2: diffWith returns $diff2
        $diff1 = $this->createMock(Specification::class);
        $diff2 = $this->createMock(Specification::class);

        $host1->method('getRemaining')->willReturn($spec1);
        $host2->method('getRemaining')->willReturn($spec2);

        $spec1->expects($this->once())->method('diffWith')->with($vmSpec)->willReturn($diff1);
        $spec2->expects($this->once())->method('diffWith')->with($vmSpec)->willReturn($diff2);

        // diff1->compareTo(diff2) === 1 => diff2 ist besser (kleiner)
        $diff1->method('compareTo')->with($diff2)->willReturn(1);
        $diff2->method('compareTo')->with($diff1)->willReturn(-1);

        // Host 2 ist der beste Fit, also sollte addVM nur bei Host 2 aufgerufen werden
        $host1->expects($this->never())->method('addVM');
        $host2->expects($this->once())->method('addVM')->with($vm);

        $algorithm = new BestFitAlgorithm();
        $result = $algorithm->add($vm, [$host1, $host2]);
        self::assertTrue($result);
    }

    public function testAddReturnsFalseIfNoHosts()
    {
        $vm = $this->createMock(SpecificationAwareInterface::class);
        $algorithm = new BestFitAlgorithm();
        self::assertFalse($algorithm->add($vm, []));
    }
}
