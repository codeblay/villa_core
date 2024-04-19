<?php

namespace App\Services\Mutation\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\Mutation;
use App\Repositories\MutationRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class Update extends Service
{
    const CONTEXT           = "menyimpan mutasi";
    const MESSAGE_SUCCESS   = "berhasil menyimpan mutasi";
    const MESSAGE_ERROR     = "gagal menyimpan mutasi";

    const RULES_VALIDATOR = [
        'id'        => 'required|integer',
        'amount'    => 'required|integer',
    ];

    const ATTRIBUTES_VALIDATOR = [
        'amount' => 'nominal',
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

            $mutation = MutationRepository::first(['id' => $this->request->id]);
            if (!$mutation) {
                DB::rollBack();
                return parent::error("mutasi tidak ditemukan");
            }

            if (($balance - $mutation->amount) < $this->request->amount) {
                DB::rollBack();
                return parent::error("jumlah pencairan tidak boleh melebihi saldo investor");
            }

            MutationRepository::update($this->request->id, [
                'amount' => - ($this->request->amount),
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
