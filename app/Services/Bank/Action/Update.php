<?php

namespace App\Services\Bank\Action;

use App\Base\Service;
use App\Models\Bank;
use App\Models\DTO\ServiceResponse;
use App\MyConst;
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

        if (config('payment.method') == MyConst::PAYMENT_MANUAL) {
            if ($this->request->id == 1) {
                $rules['va_number'] = 'required|image';
            } else {
                if ($this->request->is_active) {
                    $rules['va_number'] = 'required|string';
                } else {
                    $rules['va_number'] = 'sometimes|nullable|string';
                }
            }
        }

        return $rules;
    }

    private function rulesAttributes() : array {
        $attribtues = [
            'is_active' => 'status',
            'va_number' => 'rekening',
        ];

        if ($this->request->id == 1) {
            $attribtues['va_number'] = 'gambar QR';
        }

        return $attribtues;
    }

    public function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $validator = parent::validator($this->request->all(), $this->rulesValidator(), attributes: $this->rulesAttributes());
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $data = $validator->validated();
            if ($this->request->id == 1 && (config('payment.method') == MyConst::PAYMENT_MANUAL)) {
                $bank = BankRepository::first(['id' => $this->request->id]);
                if (file_exists(public_path($bank->va_number))) unlink($bank->va_number);

                $qr = $this->request->file('va_number');
                $file_name = "qr.{$qr->getClientOriginalExtension()}";

                $qr->move('image', $file_name);
                $data['va_number'] = "image/$file_name";
            }

            BankRepository::update($this->request->id, $data);

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
