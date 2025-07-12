<?php

namespace App\Service;

use App\Service\Interfaces\FileUploadServiceInterface;
use App\Core\Errors\ErrorMessages;

class FileUploadService implements FileUploadServiceInterface
{
    private string $uploadPath;
    private array $allowedTypes;
    private int $maxSize;

    public function __construct()
    {
        $this->uploadPath = __DIR__ . '/../../public/images/uploads/';
        $this->allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $this->maxSize = 5 * 1024 * 1024; // 5MB

        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }
    }

    public function upload(array $file, string $directory = '', string $prefix = ''): array
    {
        $validationErrors = $this->validate($file);
        if (!empty($validationErrors)) {
            return ['success' => false, 'error' => implode(', ', $validationErrors)];
        }

        $targetDir = $this->uploadPath . $directory;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $prefix . '_' . uniqid() . '.' . $extension;
        $destination = $targetDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'success' => true,
                'filename' => $filename,
                'path' => '/images/uploads/' . $directory . '/' . $filename,
                'fullPath' => $destination
            ];
        }

        return ['success' => false, 'error' => ErrorMessages::get('file_upload_error')];
    }

    public function delete(string $path): bool
    {
        $fullPath = $this->uploadPath . $path;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }

    public function validate(array $file): array
    {
        $errors = [];

        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $errors[] = ErrorMessages::get('file_upload_error');
        }

        if (!in_array($file['type'], $this->allowedTypes)) {
            $errors[] = ErrorMessages::get('file_type_invalid');
        }

        if ($file['size'] > $this->maxSize) {
            $errors[] = ErrorMessages::get('file_size_invalid');
        }

        return $errors;
    }
}