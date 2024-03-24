<?php

namespace App\Models\DTO;

final class SearchTransaction
{
    public ?string $code;
    public ?string $created_at;
    public ?int $status;
    public ?string $villa_id;
    public ?string $start_date;
    public ?string $end_date;
}
