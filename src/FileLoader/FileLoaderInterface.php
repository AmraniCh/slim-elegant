<?php

namespace App\Kernel\FileLoader;

interface FileLoaderInterface
{
    public function load(string $filePath): array;
    public function loadWithVariables(string $filePath, array $variables = []): array;
}