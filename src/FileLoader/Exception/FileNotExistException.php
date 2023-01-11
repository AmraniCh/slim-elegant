<?php

namespace App\Kernel\FileLoader\Exception;

class FileNotExistException extends FileLoaderException
{

    public function __construct(string $filePath, string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        $message = "Failed to load the file with path '$filePath' because is not exist.";
        parent::__construct($message, $code, $previous);
    }
}