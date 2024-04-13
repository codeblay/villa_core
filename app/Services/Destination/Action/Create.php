<?php

namespace App\Services\Destination\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\File;
use App\Repositories\DestinationRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

final class Create extends Service
{
    const CONTEXT           = "menyimpan destinasi";
    const MESSAGE_SUCCESS   = "berhasil menyimpan destinasi";
    const MESSAGE_ERROR     = "gagal menyimpan destinasi";

    const RULES_VALIDATOR = [
        'name'                      => 'required|string',
        'city_id'                   => 'required|integer',
        'destination_category_id'   => 'required|integer',
        'description'               => 'required|string|min:20',
        'image'                     => 'required|mimes:jpg|max:1024',
    ];

    public function __construct(protected Request $request)
    {
    }

    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $validator = parent::validator($this->request->all(), self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $destination = DestinationRepository::create([
                'name'                      => $this->request->name,
                'city_id'                   => $this->request->city_id,
                'destination_category_id'   => $this->request->destination_category_id,
                'description'               => $this->request->description,
            ]);

            $file = $this->request->file('image');

            $_file       = new File();
            $_file->path = $file->store(options: 'destination');
            $_file->type = File::TYPE_IMAGE;
    
            $destination->file()->save($_file);

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
