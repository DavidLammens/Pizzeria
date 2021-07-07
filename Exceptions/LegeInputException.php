<?php

declare(strict_types=1);

namespace Exceptions;

use Exception;
use Throwable;

class LegeInputException extends Exception
{
    private string $veld;

    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
        string $veld = ""
    ) {
        parent::__construct($message, $code, $previous);
        $this->veld = $veld;
    }

    public function getVeld(): string
    {
        return $this->veld;
    }
}