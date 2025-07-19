<?php

namespace App\Core;

class FileUpload
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
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'error' => 'Aucun fichier uploadé'];
        }

        if (!in_array($file['type'], $this->allowedTypes)) {
            return ['success' => false, 'error' => 'Type de fichier non autorisé'];
        }

        if ($file['size'] > $this->maxSize) {
            return ['success' => false, 'error' => 'Fichier trop volumineux (max 5MB)'];
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

        return ['success' => false, 'error' => 'Erreur lors de l\'upload'];
    }

    public function validate(array $file): array
    {
        $errors = [];

        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $errors[] = 'Aucun fichier uploadé';
        }

        if (!in_array($file['type'], $this->allowedTypes)) {
            $errors[] = 'Type de fichier non autorisé';
        }

        if ($file['size'] > $this->maxSize) {
            $errors[] = 'Fichier trop volumineux';
        }

        return $errors;
    }
}