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
     * 
     * @throws \Exception throws an exception if the configuration file not returning an array.
     */
    public function loadConfiguration(string $filePath, array $variables = []): array;
}