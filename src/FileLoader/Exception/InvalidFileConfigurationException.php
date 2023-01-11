<?php

namespace App\Kernel\FileLoader\Exception;

class InvalidFileConfigurationException extends FileLoaderException
{

    public function __construct(string $filePath, string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        $message = "File '$filePath' contains invalid configuration.";
        parent::__construct($message, $code, $previous);
    }
}