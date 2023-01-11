<?php

namespace App\Kernel\FileLoader;

use App\Kernel\FileLoader\Exception\FileNotExistException;
use App\Kernel\FileLoader\Exception\InvalidFileDataException;

class FileLoader implements FileLoaderInterface
{

    public function load(string $filePath): array
    {
        self::throwIfNotExist($filePath);

        $data = self::doRequire($filePath);

        if (!is_array($data)) {
            throw new InvalidFileDataException($filePath);
        }

        return $data;
    }

    public function loadWithVariables(string $filePath, array $variables = []): array
    {
        self::throwIfNotExist($filePath);

        foreach ($variables as $name => $value) {
            ${$name} = $value;
        }

        return self::doRequire($filePath);
    }

    /**
     * @throws FileNotExistException
     */
    private function throwIfNotExist(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new FileNotExistException($filePath);
        }
    }

    private function doRequire(string $filePath)
    {
        return require($filePath);
    }
}
