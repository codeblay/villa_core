<?php

namespace App\Services\Bank\Action;

use App\Base\Service;
use App\Models\Bank;
use App\Models\DTO\ServiceResponse;
use App\Repositories\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class Update extends Service
{
    const CONTEXT           = "menyimpan pembayaran";
    const MESSAGE_SUCCESS   = "berhasil menyimpan pembayaran";
    const MESSAGE_ERROR     = "gagal menyimpan pembayaran";

    private function rulesValidator() : array {
        $rules = [
            'id'        => 'required|integer',
            'fee'       => 'nullable|integer',
            'is_active' => 'required|boolean',
        ];

        return $rules;
    }

    const RULES_ATTRIBUTES = [
        'is_active' => 'status',
    ];

    public function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $validator = parent::validator($this->request->all(), $this->rulesValidator(), attributes: self::RULES_ATTRIBUTES);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            BankRepository::update($this->request->id, $validator->validated());

            $active = BankRepository::get(['is_active' => true]);
            if (count($active) == 0) return parent::error("Minimal mengaktifkan 1 channel pembayaran");

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
