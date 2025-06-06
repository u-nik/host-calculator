<?php

declare(strict_types=1);

namespace Tests\HostCalculator\Algorithm;

use App\HostCalculator\Host;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use App\HostCalculator\Algorithm\FirstFitAlgorithm;
use App\HostCalculator\Specification\SpecificationAwareInterface;

#[CoversClass(FirstFitAlgorithm::class)]
class FirstFitAlgorithmTest extends TestCase
{
    public function testAddAssignsVmToFirstHostThatAcceptsIt()
    {
        $vm = $this->createMock(SpecificationAwareInterface::class);

        $host1 = $this->getMockBuilder(Host::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['addVM'])
            ->getMock();
        $host2 = $this->getMockBuilder(Host::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['addVM'])
            ->getMock();

        // Host 1 nimmt die VM nicht, Host 2 nimmt sie
        $host1->expects($this->once())->method('addVM')->with($vm)->willReturn(false);
        $host2->expects($this->once())->method('addVM')->with($vm)->willReturn(true);

        $algorithm = new FirstFitAlgorithm();
        $result = $algorithm->add($vm, [$host1, $host2]);
        self::assertTrue($result);
    }

    public function testAddReturnsFalseIfNoHostAcceptsVm()
    {
        $vm = $this->createMock(SpecificationAwareInterface::class);

        $host1 = $this->getMockBuilder(Host::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['addVM'])
            ->getMock();
        $host2 = $this->getMockBuilder(Host::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['addVM'])
            ->getMock();

        $host1->expects($this->once())->method('addVM')->with($vm)->willReturn(false);
        $host2->expects($this->once())->method('addVM')->with($vm)->willReturn(false);

        $algorithm = new FirstFitAlgorithm();
        $result = $algorithm->add($vm, [$host1, $host2]);
        self::assertFalse($result);
    }

    public function testAddReturnsFalseIfNoHosts()
    {
        $vm = $this->createMock(SpecificationAwareInterface::class);
        $algorithm = new FirstFitAlgorithm();
        self::assertFalse($algorithm->add($vm, []));
    }
}
