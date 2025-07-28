<?php

namespace App\Service;

use App\Service\Interfaces\UserServiceInterface;
use App\Service\Interfaces\ValidationServiceInterface;
use App\Service\Interfaces\FileUploadServiceInterface;
use App\Service\Interfaces\SmsServiceInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Entite\Utilisateur;
use App\Entite\TypeUser;
use App\Core\Errors\ErrorMessages;

class UserService implements UserServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private ValidationServiceInterface $validationService;
    private FileUploadServiceInterface $fileUploadService;
    private SmsServiceInterface $smsService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        FileUploadServiceInterface $fileUploadService,
        SmsServiceInterface $smsService
    ) {
        $this->userRepository = $userRepository;
        $this->fileUploadService = $fileUploadService;
        $this->smsService = $smsService;
    }

    public function register(array $userData): array
    {
        // Vérification des champs requis
        $requiredFields = ['nom', 'prenom', 'numero', 'adresse', 'numero_carte_identite'];
        foreach ($requiredFields as $field) {
            if (empty($userData[$field])) {
                return [
                    'success' => false,
                    'errors' => [$field => "Le champ $field est requis"]
                ];
            }
        }

        // Vérifier si l'utilisateur existe déjà
        $existingUser = $this->findUserByPhone($userData['numero']);
        if ($existingUser) {
            return [
                'success' => false,
                'errors' => ['numero' => 'Ce numéro est déjà utilisé']
            ];
        }

        // Upload des photos
        if (!empty($_FILES)) {
            $uploadResults = $this->handlePhotoUploads($_FILES);
            if (!$uploadResults['success']) {
                return ['success' => false, 'errors' => $uploadResults['errors']];
            }
            $userData = array_merge($userData, $uploadResults['photos']);
        }

        // Stocker les données en session pour l'étape suivante
        $_SESSION['register_data'] = $userData;
        
        return [
            'success' => true,
            'redirect' => '/code-secret' // Add explicit redirect path
        ];
    }

    public function createUser(array $userData): array
    {
        try {
            if (empty($userData['code_secret'])) {
                return [
                    'success' => false,
                    'errors' => ['code_secret' => 'Le code secret est requis']
                ];
            }

            // Hash du code secret
            $userData['code'] = password_hash($userData['code_secret'], PASSWORD_DEFAULT);

            $user = new Utilisateur(
                0,
                $userData['code'],
                $userData['nom'],
                $userData['prenom'],
                $userData['numero'],
                $userData['adresse'],
                TypeUser::CLIENT->value,
                $userData['photo_identite_recto'] ?? null,
                $userData['photo_identite_verso'] ?? null,
                $userData['numero_carte_identite']
            );

            if ($this->userRepository->create($user)) {
                return ['success' => true];
            }

            return [
                'success' => false,
                'errors' => ['general' => 'Erreur lors de la création du compte']
            ];

        } catch (\Exception $e) {
            error_log('Erreur création utilisateur: ' . $e->getMessage());
            return [
                'success' => false,
                'errors' => ['general' => 'Une erreur est survenue']
            ];
        }
    }

    public function login(string $code): array
    {
        // Valider le format du code
        if (!preg_match('/^[0-9]{4}$/', $code)) {
            return [
                'success' => false,
                'errors' => ['code' => 'Le code doit contenir exactement 4 chiffres']
            ];
        }

        // Utiliser la nouvelle méthode qui vérifie le hash
        $user = $this->userRepository->findByCode($code);

        if (!$user) {
            return [
                'success' => false,
                'errors' => ['code' => 'Code secret invalide']
            ];
        }

        return [
            'success' => true,
            'user' => [
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'numero' => $user->getNumero(),
                'type' => $user->getTypeUser()->value
            ]
        ];
    }

    public function findUserByCode(string $code): ?Utilisateur
    {
        return $this->userRepository->findByCode($code);
    }

    public function findUserByPhone(string $phone): ?Utilisateur
    {
        return $this->userRepository->findByPhone($phone);
    }

    private function handlePhotoUploads(array $files): array
    {
        $photos = [];
        $errors = [];

        foreach (['photo_identite_recto', 'photo_identite_verso'] as $fileKey) {
            if (isset($files[$fileKey]) && $files[$fileKey]['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->fileUploadService->upload($files[$fileKey], 'cni', $fileKey);
                if ($uploadResult['success']) {
                    $photos[$fileKey] = $uploadResult['filename'];
                } else {
                    $errors[$fileKey] = $uploadResult['error'];
                }
            }
        }

        return [
            'success' => empty($errors),
            'photos' => $photos,
            'errors' => $errors
        ];
    }
}