<?php

namespace App\Services\Dashboard\Action;

use App\Base\Service;
use App\Models\DTO\SearchVilla;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller as ModelsSeller;
use App\Models\Transaction;
use App\Models\Villa;
use App\Repositories\TransactionRepository;
use App\Repositories\VillaRepository;
use Illuminate\Http\Response;

final class Seller extends Service
{
    const CONTEXT           = "memuat dashboard";
    const MESSAGE_SUCCESS   = "berhasil memuat dashboard";
    const MESSAGE_ERROR     = "gagal memuat dashboard";

    function __construct(protected ModelsSeller $seller)
    {
    }

    function call(): ServiceResponse
    {
        try {

            $this->data = [
                'need_confirmation' => $this->needConfirmation(),
                'transaction_month' => [
                    'total' => TransactionRepository::totalThisMonthBySeller($this->seller->id),
                    'value' => TransactionRepository::valueThisMonthBySeller($this->seller->id),
                ],
                'villa' => $this->villa(),
            ];

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    private function needConfirmation() : array {
        $data = TransactionRepository::needConfirmation($this->seller->id);
        return $data->map(function(Transaction $transaction){
            return [
                'id'        => $transaction->id,
                'code'      => $transaction->code,
                'villa'     => $transaction->villa->name,
                'amount'    => $transaction->amount,
            ];
        })->toArray();
    }
    
    private function villa() : array {
        $data = VillaRepository::cursorBySeller($this->seller->id, new SearchVilla, 3);
        return $data->map(function(Villa $villa){
            return [
                'id'            => $villa->id,
                'name'          => $villa->name,
                'address'       => $villa->city->address,
                'price'         => $villa->price,
                'status'        => strtolower($villa->publish_label),
                'description'   => $villa->description,
            ];
        })->toArray();
    }
}
