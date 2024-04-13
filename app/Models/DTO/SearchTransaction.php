<?php

namespace App\Models\DTO;

final class SearchTransaction
{
    public ?string $code        = null;
    public ?string $created_at  = null;
    public ?int $status         = null;
    public ?string $villa_id    = null;
    public ?string $start_date  = null;
    public ?string $end_date    = null;
}
