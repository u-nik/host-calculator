<?php
declare(strict_types=1);

namespace App\HostCalculator;

use App\HostCalculator\Specification\Specification;
use App\HostCalculator\Specification\SpecificationAwareHostInterface;

class Host implements SpecificationAwareHostInterface
{
    /**
     * @var \App\HostCalculator\Specification\SpecificationAwareHostInterface[]
     */
    protected array $vms = [];

    /**
     * @var \App\HostCalculator\Specification\Specification The remaining resources of the host after adding VMs.
     */
    protected Specification $remaining;

    /**
     * Host constructor.
     *
     * @param string $id The unique identifier for the host.
     * @param \App\HostCalculator\Specification\Specification $specification The specification of the host.
     */
    public function __construct(
        public readonly string $id,
        public readonly Specification $specification,
    ) {
        $this->remaining = clone $this->specification;
    }

    /**
     * Adds a virtual machine to the host.
     *
     * @param \App\HostCalculator\Specification\SpecificationAwareHostInterface $vm
     * @return bool Returns true if the VM was added successfully, false otherwise.
     * @throws \App\HostCalculator\VMResourceException
     */
    public function addVM(SpecificationAwareHostInterface $vm): bool
    {
        if (!$vm->getSpecification()->fitsIn($this->specification)) {
            throw new VMResourceException('VM cannot be added to host due to resource constraints.');
        }

        $remaining = $this->remaining->diffWith($vm->getSpecification());

        if ($remaining->isExhausted()) {
            return false;
        }

        $this->vms[]     = $vm;
        $this->remaining = $remaining;

        return true;
    }

    /**
     * Returns the ids of the VMs hosted on this host.
     *
     * @return array<string>
     */
    public function getVMIds(): array
    {
        return array_map(fn(SpecificationAwareHostInterface $vm) => $vm->getId(), $this->vms);
    }

    /**
     * Returns the specification of the VM.
     *
     * @return \App\HostCalculator\Specification\Specification
     */
    public function getSpecification(): Specification
    {
        return $this->specification;
    }


    public function getId(): string
    {
        return $this->id;
    }
}
