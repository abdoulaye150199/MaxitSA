<?php

namespace App\Service\Interfaces;

interface FileUploadServiceInterface
{
    public function upload(array $file, string $directory = '', string $prefix = ''): array;
    public function delete(string $path): bool;
    public function validate(array $file): array;
}