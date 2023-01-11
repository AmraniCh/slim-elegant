<?php

namespace App\Kernel\FileLoader\Exception;

class InvalidFileDataException extends FileLoaderException
{

    public function __construct(string $filePath, string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        $message = "Data returned from the file '$filePath' should be a value of type array.";
        parent::__construct($message, $code, $previous);
    }
}