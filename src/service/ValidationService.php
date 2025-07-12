<?php

namespace App\Service;

use App\Service\Interfaces\ValidationServiceInterface;
use App\Core\Validator;

class ValidationService implements ValidationServiceInterface
{
    public function validateRegistration(array $data): array
    {
        $errors = [];

        // Validation du numéro de téléphone
        if (empty($data['numero'])) {
            $errors['numero'] = 'Le numéro de téléphone est requis';
        } elseif (!preg_match('/^[0-9]{9,}$/', $data['numero'])) {
            $errors['numero'] = 'Le format du numéro est invalide';
        }

        // Autres validations
        if (empty($data['nom'])) {
            $errors['nom'] = 'Le nom est requis';
        }
        
        if (empty($data['prenom'])) {
            $errors['prenom'] = 'Le prénom est requis';
        }
        
        if (empty($data['adresse'])) {
            $errors['adresse'] = 'L\'adresse est requise';
        }
        
        if (empty($data['numero_carte_identite'])) {
            $errors['numero_carte_identite'] = 'Le numéro d\'identité est requis';
        }

        return $errors;
    }

    public function validateLogin(array $data): array
    {
        return Validator::validate($data, [
            'code' => [
                'required' => null,
                'code_secret' => null
            ]
        ]);
    }

    public function validateCodeSecret(array $data): array
    {
        $errors = [];
        
        if (empty($data['code_secret'])) {
            $errors['code_secret'] = 'Le code secret est requis';
        } elseif (!preg_match('/^[0-9]{4}$/', $data['code_secret'])) {
            $errors['code_secret'] = 'Le code secret doit contenir exactement 4 chiffres';
        }
        
        return $errors;
    }
}