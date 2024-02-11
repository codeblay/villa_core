<?php

namespace App\Models\DTO;

final class SearchVilla
{
    public ?string   $name;
    public ?int      $city_id;
    public ?string   $order_by      = 'created_at';
    public ?string   $order_type    = 'asc';
}
