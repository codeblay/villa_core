<?php

namespace App\Services\Mutation\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Repositories\MutationRepository;
use App\Repositories\SellerRepository;
use Illuminate\Http\Response;

final class ListForAdmin extends Service
{
    const CONTEXT           = "memuat mutasi";
    const MESSAGE_SUCCESS   = "berhasil memuat mutasi";
    const MESSAGE_ERROR     = "gagal memuat mutasi";

    public function __construct(protected int $seller_id)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $this->data = [
                "seller"    => SellerRepository::first(['id' => $this->seller_id]),
                "balance"   => MutationRepository::activeBalanceSelelr($this->seller_id),
                "mutations" => MutationRepository::listForAdmin($this->seller_id, 10)
            ];

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
