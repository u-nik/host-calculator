<?php
declare(strict_types=1);

namespace App\HostCalculator;

/**
 * Exception class for VM resource-related errors.
 * It is thrown when a virtual machine cannot be added to a host due to resource constraints.
 */
class VMResourceException extends \Exception
{

}
