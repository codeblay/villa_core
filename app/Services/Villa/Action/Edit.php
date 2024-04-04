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
use Illuminate\Support\Facades\Storage;

final class Edit extends Service
{
    const CONTEXT           = "menyimpan villa";
    const MESSAGE_SUCCESS   = "berhasil menyimpan villa";
    const MESSAGE_ERROR     = "gagal menyimpan villa";

    const RULES_VALIDATOR = [
        'id'            => 'required|integer',
        'name'          => 'sometimes|string',
        'city_id'       => 'sometimes|integer',
        'description'   => 'sometimes|string|min:20',
        'price'         => 'sometimes|integer',
        'is_publish'    => 'sometimes|boolean',
        'facilities'    => 'sometimes|array|min:1',
        'facilities.*'  => 'sometimes|integer',
        'images'        => 'sometimes|array|min:1',
        'images.*'      => 'sometimes|mimes:jpg|max:1024|dimensions:ratio=16/9',
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
            if (!$villa || ($villa->seller_id != $this->seller->id)) return parent::error("villa tidak ditemukan");

            $data = $this->request->except([
                'images',
                'facilities',
            ]);
            VillaRepository::update($villa->id, $data);

            if ($this->request->facilities) {
                $villa->facilities()->sync($this->request->facilities);
            }

            $images = $this->request->file('images');

            if ($images) {
                foreach ($villa->files as $file) {
                    $file->delete();
                    Storage::disk('villa')->delete($file->path);
                }
                foreach ($images as $image) {
                    $_file       = new File();
                    $_file->path = $image->store(options: 'villa');
                    $_file->type = File::TYPE_IMAGE;

                    $villa->files()->save($_file);
                }
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
