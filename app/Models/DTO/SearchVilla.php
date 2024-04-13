<?php

namespace App\Models\DTO;

final class SearchVilla
{
    public ?string   $name          = null;
    public ?int      $city_id       = null;
    public ?int      $seller_id     = null;
    public ?int      $rating        = null;
    public ?bool     $is_publish    = null;
    public ?string   $order_by      = 'created_at';
    public ?string   $order_type    = 'asc';
}
