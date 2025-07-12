<?php

namespace App\Service\Interfaces;

interface SmsServiceInterface
{
    public function sendWelcome(string $phoneNumber, string $name): array;
    public function sendVerificationCode(string $phoneNumber, string $code): array;
    public function sendTransactionNotification(string $phoneNumber, string $type, float $amount, float $newBalance): array;
}