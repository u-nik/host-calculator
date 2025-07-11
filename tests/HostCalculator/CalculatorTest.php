<?php

declare(strict_types=1);

namespace Tests\HostCalculator;

use App\HostCalculator\Algorithm\AlgorithmInterface;
use App\HostCalculator\Algorithm\BestFitAlgorithm;
use App\HostCalculator\Algorithm\FirstFitAlgorithm;
use App\HostCalculator\Calculator;
use App\HostCalculator\Specification\Specification;
use App\HostCalculator\Specification\SpecificationFactory;
use App\HostCalculator\VM;
use App\HostCalculator\VMResourceException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Calculator::class)]
class CalculatorTest extends TestCase
{

    /**
     * Testet die Berechnung der Anzahl der benötigten Hosts für die gegebenen VMs.
     *
     * @param \App\HostCalculator\Specification\Specification $hostSpecs
     * @param VM[] $vms
     * @param int $expectedCount
     * @param array $expectedDistribution
     * @return void
     * @throws \App\HostCalculator\VMResourceException
     */
    #[DataProvider('calculationDataProvider')]
    public function testCalculation(
        AlgorithmInterface $algorithm,
        Specification $hostSpecs,
        array $vms,
        int $expectedCount,
        array $expectedDistribution
    ): void {

        $calculator = new Calculator($hostSpecs, $algorithm);
        $result     = $calculator->calculateHosts($vms);

        self::assertSame($expectedCount, $result->hostCount);
        self::assertSame($expectedDistribution, $result->getVmDistribution());
    }

    /**
     * Testet, ob die Ausnahme geworfen wird, wenn eine VM zu groß für den Host ist, sie also auch alleine nicht auf dem Host laufen kann.
     *
     * @return void
     */
    public function testCalculationExceptionWithOversizedVMs(): void
    {
        self::expectException(VMResourceException::class);
        $calculator = new Calculator(SpecificationFactory::createWithAll(1), new FirstFitAlgorithm());

        $vms = [
            new VM('vm', SpecificationFactory::createWithAll(2)),
        ];

        $calculator->calculateHosts($vms);
    }

    /**
     * Liefert die Testdaten für die Berechnung der Hosts.
     *
     * @return array
     */
    public static function calculationDataProvider(): array
    {
        $specs1 = SpecificationFactory::createWithAll(1);
        $specs2 = SpecificationFactory::createWithAll(2);
        $specs3 = SpecificationFactory::createWithAll(3);
        $specs4 = SpecificationFactory::createWithAll(4);

        $vms1 = [
            new VM('vm-1', $specs1),
            new VM('vm-2', $specs1),
            new VM('vm-3', $specs1),
        ];

        return [
            'test first fit cpu 1'           => [
                'algorithm'            => new FirstFitAlgorithm(),
                'hostSpecs'            => SpecificationFactory::createWith(2, 3, 3),
                'vms'                  => $vms1,
                'expectedCount'        => 2,
                'expectedDistribution' => [
                    'host-1' => ['vm-1', 'vm-2'],
                    'host-2' => ['vm-3'],
                ],
            ],
            'test first fit memory 1'        => [
                'algorithm'            => new FirstFitAlgorithm(),
                'hostSpecs'            => SpecificationFactory::createWith(3, 2, 3),
                'vms'                  => $vms1,
                'expectedCount'        => 2,
                'expectedDistribution' => [
                    'host-1' => ['vm-1', 'vm-2'],
                    'host-2' => ['vm-3'],
                ],
            ],
            'test first fit bandwith 1'      => [
                'algorithm'            => new FirstFitAlgorithm(),
                'hostSpecs'            => SpecificationFactory::createWith(3, 3, 2),
                'vms'                  => $vms1,
                'expectedCount'        => 2,
                'expectedDistribution' => [
                    'host-1' => ['vm-1', 'vm-2'],
                    'host-2' => ['vm-3'],
                ],
            ],
            'test first fit same sized vms'  => [
                'algorithm'            => new FirstFitAlgorithm(),
                'hostSpecs'            => $specs1,
                'vms'                  => $vms1,
                'expectedCount'        => 3,
                'expectedDistribution' => [
                    'host-1' => ['vm-1'],
                    'host-2' => ['vm-2'],
                    'host-3' => ['vm-3'],
                ],
            ],
            'test first fit different sizes' => [
                'algorithm'            => new FirstFitAlgorithm(),
                'hostSpecs'            => $specs4,
                'vms'                  => [
                    new VM('vm-1', $specs1), // -> Host 1
                    new VM('vm-2', $specs4), // -> Host 2
                    new VM('vm-3', $specs2), // -> Host 1
                ],
                'expectedCount'        => 2,
                'expectedDistribution' => [
                    'host-1' => ['vm-1', 'vm-3'],
                    'host-2' => ['vm-2'],
                ],
            ],
            'test best fit 1'    => [
                'algorithm'            => new BestFitAlgorithm(),
                'hostSpecs'            => $specs4,
                'vms'                  => [
                    new VM('vm-1', $specs2), // -> Host 1
                    new VM('vm-2', $specs3), // -> Host 2
                    new VM('vm-3', $specs1), // -> Host 2
                ],
                'expectedCount'        => 2,
                'expectedDistribution' => [
                    'host-1' => ['vm-1'],
                    'host-2' => ['vm-2', 'vm-3'],
                ],
            ],
        ];
    }
}
