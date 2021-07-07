<?php

declare(strict_types=1);

namespace Exceptions;

use Exceptions\LegeInputException;
use Throwable;

class TeVeelInputException extends LegeInputException
{
    private int $maxInput;

    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
        string $veld = "",
        int $maxInput = 0
    ) {
        parent::__construct($message, $code, $previous, $veld);
        $this->maxInput = $maxInput;
    }

    public function getMaxInput(): int
    {
        return $this->maxInput;
    }
}
