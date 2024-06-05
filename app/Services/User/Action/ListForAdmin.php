<?php

namespace App\Services\User\Action;

use App\Base\Service;
use App\Models\DTO\SearchAgent;
use App\Models\DTO\ServiceResponse;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

final class ListForAdmin extends Service
{
    const CONTEXT           = "memuat agen";
    const MESSAGE_SUCCESS   = "berhasil memuat agen";
    const MESSAGE_ERROR     = "gagal memuat agen";

    public function __construct(protected Request $request)
    {
        $this->data['users'] = new LengthAwarePaginator([], 0, 20);
    }

    function call(): ServiceResponse
    {
        try {
            $param = new SearchAgent;
            $param->name = $this->request->name;
            $this->data['users'] = UserRepository::listAgentAdmin(20, $param);

            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
