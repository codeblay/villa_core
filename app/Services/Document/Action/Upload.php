<?php

namespace App\Services\Document\Action;

use App\Base\Service;
use App\Models\Bank;
use App\Models\DTO\ServiceResponse;
use App\MyConst;
use App\Repositories\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class Upload extends Service
{
    const CONTEXT           = "menyimpan dokumen";
    const MESSAGE_SUCCESS   = "berhasil menyimpan dokumen";
    const MESSAGE_ERROR     = "gagal menyimpan dokumen";

    const RULES_VALIDATOR = [
        'document' => 'required|mimes:pdf'
    ];

    public function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $document = $this->request->file('document');

            $document->move('pdf', MyConst::DOCUMENT_VERIFICATION_NAME);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
