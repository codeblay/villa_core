<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\Seller;
use App\Repositories\VillaRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class Edit extends Service
{
    const CONTEXT           = "edit villa";
    const MESSAGE_SUCCESS   = "berhasil edit villa";
    const MESSAGE_ERROR     = "gagal edit villa";

    const RULES_VALIDATOR = [
        'id'            => 'required',
        'name'          => 'required|string',
        'city_id'       => 'required|integer',
        'description'   => 'required|string|min:20',
        'price'         => 'required|integer',
        'facilities'    => 'required|array|min:1',
        'facilities.*'  => 'required|integer',
    ];

    public function __construct(protected Request $request, protected Seller $seller)
    {
    }

    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());
            
            $villa = VillaRepository::first(['id' => $this->request->id]);
            if (!$villa) return parent::error("villa tidak ditemukan");

            VillaRepository::update($villa->id, [
                'name'          => $this->request->name,
                'seller_id'     => $this->seller->id,
                'city_id'       => $this->request->city_id,
                'description'   => $this->request->description,
                'price'         => $this->request->price,
                'is_publish'    => false,
                'is_available'  => false,
            ]);

            $villa->facilities()->sync($this->request->facilities);

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
