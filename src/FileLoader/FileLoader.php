<?php

namespace App\Kernel\FileLoader;

use App\Kernel\FileLoader\Exception\FileNotExistException;
use App\Kernel\FileLoader\Exception\InvalidFileConfigurationException;
use App\Kernel\FileLoader\Exception\InvalidFileDataException;

class FileLoader implements FileLoaderInterface
{

    public function load(string $filePath, array $variables = [])
    {
        self::throwIfNotExist($filePath);

        $data = self::doRequire($filePath, $variables);

        return $data;
    }

    /**
     * @throws InvalidFileConfigurationException
     */
    public function loadConfiguration(string $filePath, array $variables = []): array
    {
        self::throwIfNotExist($filePath);

        $data = self::doRequire($filePath, $variables);

        if (!is_array($data)) {
            throw new InvalidFileConfigurationException($filePath);
        }

        return $data;
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

    private function doRequire(string $filePath, array $variables = [])
    {
        if (!empty($variables)) {
            foreach ($variables as $name => $value) {
                ${$name} = $value;
            }
        }

        return require($filePath);
    }
}
