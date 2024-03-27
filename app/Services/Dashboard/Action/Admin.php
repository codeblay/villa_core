<?php

namespace App\Services\Dashboard\Action;

use App\Base\Service;
use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\Models\Transaction;
use App\Repositories\BuyerRepository;
use App\Repositories\SellerRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\VillaRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

final class Admin extends Service
{
    const CONTEXT           = "load dashboard";
    const MESSAGE_SUCCESS   = "berhasil load dashboard";
    const MESSAGE_ERROR     = "gagal load dashboard";

    function __construct()
    {
        $this->data = [
            'villa'         => 0,
            'seller'        => [],
            'buyer'         => [],
            'transaction'   => [],
        ];
    }

    function call(): ServiceResponse
    {
        try {

            $this->data = [
                'villa'         => $this->villaMap(),
                'seller'        => $this->sellerMap(),
                'buyer'         => $this->buyerMap(),
                'transaction'   => $this->transactionMap(),
            ];

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    function villaMap()
    {
        return VillaRepository::get()->count();
    }

    function sellerMap(): array
    {
        return SellerRepository::get()
            ->groupBy(function (Seller $seller) {
                return is_null($seller->email_verified_at) ? 'belum verifikasi' : 'verif';
            })
            ->map(function (Collection $sellers) {
                return $sellers->count();
            })
            ->toArray();
    }

    function buyerMap(): array
    {
        return BuyerRepository::get()
            ->groupBy(function (Buyer $buyer) {
                if (is_null($buyer->email_verified_at)) return 'belum verifikasi';
                if (is_null($buyer->document_verified_at)) return 'belum pengesahan';
                return 'verif';
            })
            ->map(function (Collection $buyers) {
                return $buyers->count();
            })
            ->toArray();
    }

    function transactionMap(): array
    {
        $result[] = [
            'label' => 'Sukses',
            'class' => 'success',
            'value' => TransactionRepository::count(['status' => Transaction::STATUS_SUCCESS])
        ];
        $result[] = [
            'label' => 'Gagal',
            'class' => 'danger',
            'value' => TransactionRepository::count(['status' => Transaction::STATUS_FAILED])
        ];
        $result[] = [
            'label' => 'Ditolak',
            'class' => 'warning',
            'value' => TransactionRepository::count(['status' => Transaction::STATUS_REJECT])
        ];
        $result[] = [
            'label' => 'Pending',
            'class' => 'info',
            'value' => TransactionRepository::count(['status' => Transaction::STATUS_PENDING])
        ];
        $result[] = [
            'label' => 'Baru',
            'class' => 'secondary',
            'value' => TransactionRepository::count(['status' => Transaction::STATUS_NEW])
        ];

        return $result;
    }
}
