<?php

namespace App\Services\Auth\Action;

use App\Base\Service;
use App\Interface\RepositoryApi;
use App\Models\Buyer;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\Repositories\BuyerRepository;
use App\Repositories\SellerRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UpdateFcm extends Service
{
    const CONTEXT           = "update token";
    const MESSAGE_SUCCESS   = "berhasil update token";
    const MESSAGE_ERROR     = "gagal update token";

    const RULES_VALIDATOR = [
        'fcm_token' => 'required|string',
    ];

    public function __construct(protected Request $request, protected Seller|Buyer $user)
    {
        $this->data['token'] = '';
    }

    private function repo(): RepositoryApi
    {
        if ($this->user instanceof Seller) $repo = (new SellerRepository);
        if ($this->user instanceof Buyer) $repo = (new BuyerRepository);

        return $repo;
    }

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $repo = $this->repo();
            $repo::update($this->user->id, [
                'fcm_token' => $this->request->fcm_token
            ]);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
