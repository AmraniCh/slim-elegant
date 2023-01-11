<?php

namespace App\Kernel\FileLoader;

use App\Kernel\FileLoader\Exception\FileNotExistException;
use App\Kernel\FileLoader\Exception\InvalidFileConfigurationException;

class FileLoader
{

    /**
     * Loads a file and returns its content.
     * 
     * @return mixed
     */
    public function load(string $filePath, array $variables = [])
    {
        self::throwIfNotExist($filePath);

        $data = self::doRequire($filePath, $variables);

        return $data;
    }

    /**
     * Loads a configuration file.
     * 
     * @return array
     * 
     * @throws InvalidFileConfigurationException throws an exception if the configuration file not returning an array.
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
    private function throwIfNotExist(string $filePath): void
    {
        if (!file_exists($filePath)) {
            throw new FileNotExistException($filePath);
        }
    }

    /**
     * @return mixed
     */
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
