<?php

namespace App\Services\Mutation\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\Mutation;
use App\Repositories\MutationRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class Store extends Service
{
    const CONTEXT           = "menyimpan mutasi";
    const MESSAGE_SUCCESS   = "berhasil menyimpan mutasi";
    const MESSAGE_ERROR     = "gagal menyimpan mutasi";

    const RULES_VALIDATOR = [
        'amount'    => 'required|integer',
        'commition' => 'required|integer',
    ];

    const ATTRIBUTES_VALIDATOR = [
        'amount'    => 'dana investor',
        'commition' => 'komisi raja villa',
    ];

    public function __construct(protected Request $request, protected int $seller_id)
    {
    }

    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR, [], self::ATTRIBUTES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $balance = MutationRepository::activeBalanceSeller($this->seller_id);

            if ($balance < ($this->request->amount + $this->request->commition)) {
                DB::rollBack();
                return parent::error("jumlah pencairan tidak boleh melebihi saldo investor");
            }

            MutationRepository::create([
                'seller_id' => $this->seller_id,
                'amount'    => - ($this->request->amount),
                'type'      => Mutation::TYPE_WITHDRAW,
            ]);

            MutationRepository::create([
                'seller_id' => $this->seller_id,
                'amount'    => - ($this->request->commition),
                'type'      => Mutation::TYPE_COMMISION,
            ]);

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
