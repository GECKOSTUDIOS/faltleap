<?php

declare(strict_types=1);

namespace FaltLeap;

class LeapSyntaxException extends \RuntimeException
{
    public string $sourceFile;
    public int $sourceLine;
    public string $syntaxError;

    public function __construct(string $sourceFile, int $sourceLine, string $syntaxError)
    {
        $this->sourceFile = $sourceFile;
        $this->sourceLine = $sourceLine;
        $this->syntaxError = $syntaxError;

        parent::__construct(
            "Syntax error in {$sourceFile} on line {$sourceLine}: {$syntaxError}",
            0,
            null
        );

        // Override file/line so stack trace points to the actual error location
        $this->file = $sourceFile;
        $this->line = $sourceLine;
    }
}
