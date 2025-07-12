<?php
namespace App\Entite;

enum TypeUser: string
{
    case CLIENT = 'client';
    case SERVICE_CLIENT = 'serviceClient';
}