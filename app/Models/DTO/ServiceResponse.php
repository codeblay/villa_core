<?php

namespace App\Models\DTO;

final class ServiceResponse
{
    public bool     $status;
    public int      $code;
    public string   $message;
    public array    $result;
}
