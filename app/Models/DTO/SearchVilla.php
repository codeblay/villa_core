<?php

namespace App\Models\DTO;

final class SearchVilla
{
    public ?string   $name;
    public ?int      $city_id;
    public ?int      $seller_id;
    public ?bool     $is_publish;
    public ?string   $order_by      = 'created_at';
    public ?string   $order_type    = 'asc';
}
