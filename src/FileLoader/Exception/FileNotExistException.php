<?php

namespace App\Kernel\FileLoader\Exception;

class FileNotExistException extends FileLoaderException
{

    public function __construct(string $filePath, string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        $message = "File with path '$filePath' could not be loaded because it did not exist.";
        parent::__construct($message, $code, $previous);
    }
}