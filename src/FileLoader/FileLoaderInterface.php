<?php

namespace App\Kernel\FileLoader;

interface FileLoaderInterface
{
    /**
     * Loads a file and returns its content.
     * 
     * @return mixed
     */
    public function load(string $filePath, array $variables = []);

    /**
     * Loads a configuration file.
     * 
     * @return array
     */
    public function loadConfiguration(string $filePath, array $variables = []): array;
}