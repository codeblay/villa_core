<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\File;
use App\Models\Seller;
use App\Repositories\VillaRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

final class Create extends Service
{
    const CONTEXT           = "create villa";
    const MESSAGE_SUCCESS   = "success create villa";
    const MESSAGE_ERROR     = "failed create villa";

    const RULES_VALIDATOR = [
        'name'          => 'required|string',
        'city_id'       => 'required|integer',
        'description'   => 'required|string|min:20',
        'price'         => 'required|integer',
        'facilities'    => 'required|array|min:1',
        'facilities.*'  => 'required|integer',
        'images'        => 'required|array',
        'images.*'      => 'required|mimes:jpg|max:1024|dimensions:ratio=16/9',
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

            $images = $this->request->file('images');

            $villa = VillaRepository::create([
                'uuid'          => Uuid::uuid4(),
                'name'          => $this->request->name,
                'seller_id'     => $this->seller->id,
                'city_id'       => $this->request->city_id,
                'description'   => $this->request->description,
                'price'         => $this->request->price,
                'is_publish'    => false,
            ]);

            $villa->facilities()->attach($this->request->facilities);

            foreach ($images as $image) {
                $_file       = new File();
                $_file->path = $image->store(options: 'villa');
                $_file->type = File::TYPE_IMAGE;

                $villa->files()->save($_file);
            }

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }
}
