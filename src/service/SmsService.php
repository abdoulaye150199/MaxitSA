<?php

namespace App\Service;

use App\Service\Interfaces\SmsServiceInterface;
use App\Core\Errors\ErrorMessages;

class SmsService implements SmsServiceInterface
{
    private string $accountSid;
    private string $authToken;
    private string $fromNumber;

    public function __construct()
    {
        $this->accountSid = $_ENV['TWILIO_ACCOUNT_SID'] ?? getenv('TWILIO_ACCOUNT_SID') ?: '';
        $this->authToken = $_ENV['TWILIO_AUTH_TOKEN'] ?? getenv('TWILIO_AUTH_TOKEN') ?: '';
        $this->fromNumber = $_ENV['TWILIO_FROM_NUMBER'] ?? getenv('TWILIO_FROM_NUMBER') ?: '';

        // Ne pas lever d'exception si les variables Twilio ne sont pas configurées
        // Le service fonctionnera en mode simulation
    }

    public function sendWelcome(string $phoneNumber, string $name): array
    {
        $message = "Bienvenue {$name} ! Votre compte MaxItSA a été créé avec succès. Merci de nous faire confiance.";
        return $this->send($phoneNumber, $message);
    }

    public function sendVerificationCode(string $phoneNumber, string $code): array
    {
        $message = "Votre code de vérification MaxItSA est : {$code}. Ce code expire dans 10 minutes.";
        return $this->send($phoneNumber, $message);
    }

    public function sendTransactionNotification(string $phoneNumber, string $type, float $amount, float $newBalance): array
    {
        $message = "Transaction {$type} de {$amount} FCFA effectuée. Nouveau solde : {$newBalance} FCFA.";
        return $this->send($phoneNumber, $message);
    }

    private function send(string $to, string $message): array
    {
        // Si les variables Twilio ne sont pas configurées, simuler l'envoi
        if (empty($this->accountSid) || empty($this->authToken) || empty($this->fromNumber)) {
            error_log("SMS simulé vers {$to}: {$message}");
            return [
                'success' => true,
                'sid' => 'sim_' . uniqid(),
                'status' => 'sent_simulation'
            ];
        }

        try {
            // Simulation d'envoi SMS - remplacer par l'implémentation Twilio réelle
            $data = [
                'From' => $this->fromNumber,
                'To' => $to,
                'Body' => $message
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/{$this->accountSid}/Messages.json");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_USERPWD, "{$this->accountSid}:{$this->authToken}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 201) {
                $responseData = json_decode($response, true);
                return [
                    'success' => true,
                    'sid' => $responseData['sid'] ?? null,
                    'status' => $responseData['status'] ?? 'sent'
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Erreur HTTP: ' . $httpCode
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}