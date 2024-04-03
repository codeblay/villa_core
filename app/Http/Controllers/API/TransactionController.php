<?php

namespace App\Http\Controllers\API;

use App\Base\ApiController;
use App\Services\Transaction\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends ApiController
{

    function list(Request $request): JsonResponse
    {
        $service = TransactionService::ListForBuyer($request, auth()->user());

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function detail(int $transaction_id): JsonResponse
    {
        $service = TransactionService::detail($transaction_id, auth()->user());

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function accept(int $transaction_id): JsonResponse
    {
        $service = TransactionService::accept($transaction_id);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function deny(int $transaction_id): JsonResponse
    {
        $service = TransactionService::deny($transaction_id);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    function sync(int $transaction_id): JsonResponse
    {
        $service = TransactionService::sync($transaction_id);

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }

    /**
     * Mengambil data transaksi untuk seller
     * @param Request $request
     * @return JsonResponse
     */
    function listForSeller(Request $request): JsonResponse
    {
        $service = TransactionService::ListForSeller($request, auth()->user());

        return parent::response(
            status: $service->status,
            message: $service->message,
            data: $service->data,
            http_code: $service->code,
        );
    }
}
