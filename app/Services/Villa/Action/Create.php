<?php

namespace App\Services\Villa\Action;

use App\Base\Service;
use App\Models\DTO\ServiceResponse;
use App\Models\File;
use App\Models\Seller;
use App\Models\User;
use App\Repositories\VillaRepository;
use App\Repositories\VillaTypeRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

final class Create extends Service
{
    const CONTEXT           = "menyimpan villa";
    const MESSAGE_SUCCESS   = "berhasil menyimpan villa";
    const MESSAGE_ERROR     = "gagal menyimpan villa";

    const RULES_VALIDATOR = [
        'name'                      => 'required|string',
        'city_id'                   => 'required|integer',
        'description'               => 'required|string|min:20',
        'images'                    => 'required|array|min:1',
        'images.*'                  => 'required|image|max:1024',
        'type'                      => 'required|array|min:1',
        'type.*.name'               => 'required|string',
        'type.*.total_unit'         => 'required|integer|min:1',
        'type.*.price'              => 'required|integer|min:1',
        'type.*.facilities'         => 'required|array|min:1',
        'type.*.facilities.*'       => 'required|integer',
        'type.*.description'        => 'required|string',
        'type.*.images'             => 'required|array|min:1',
        'type.*.images.*'           => 'required|image|max:1024',
    ];

    const RULES_VALIDATOR_EDIT = [
        'name'                      => 'required|string',
        'city_id'                   => 'required|integer',
        'description'               => 'required|string|min:20',
        'status'                    => 'required|boolean',
        'images'                    => 'sometimes|array|min:1',
        'images.*'                  => 'required|image|max:1024',
        'type'                      => 'required|array|min:1',
        'type.*.name'               => 'required|string',
        'type.*.total_unit'         => 'required|integer|min:1',
        'type.*.price'              => 'required|integer|min:1',
        'type.*.facilities'         => 'required|array|min:1',
        'type.*.facilities.*'       => 'required|integer',
        'type.*.description'        => 'required|string',
        'type.*.status'             => 'required|boolean',
        'type.*.images'             => 'sometimes|array|min:1',
        'type.*.images.*'           => 'required|image|max:1024',
    ];

    private bool $is_edit = false;

    public function __construct(protected Request $request, protected Seller|User $seller)
    {
        $this->is_edit = $this->request->id ? true : false;
    }

    function call(): ServiceResponse
    {
        DB::beginTransaction();
        try {
            $validator = parent::validator($this->request->all(), $this->is_edit ? self::RULES_VALIDATOR_EDIT : self::RULES_VALIDATOR);
            if ($validator->fails()) return parent::error($validator->errors()->first());

            $images = $this->request->file('images');

            if ($this->is_edit) {
                $villa = VillaRepository::first(['id' => $this->request->id]);
                VillaRepository::update($villa->id, [
                    'name'          => $this->request->name,
                    'city_id'       => $this->request->city_id,
                    'description'   => $this->request->description,
                    'is_publish'    => $this->request->status,
                ]);
            } else {
                $villa = VillaRepository::create([
                    'uuid'          => Uuid::uuid4(),
                    'name'          => $this->request->name,
                    'city_id'       => $this->request->city_id,
                    'description'   => $this->request->description,
                    'is_publish'    => true,
                ]);
            }

            if ($this->is_edit) {
                $villa->facilities()->sync($this->request->facilities);

                if ($images) {
                    foreach ($villa->files as $file) {
                        Storage::disk('villa')->delete($file->path);
                        $file->delete();
                    }
                }
            } else {
                $villa->facilities()->attach($this->request->facilities);
            }

            foreach ($images ?? [] as $image) {
                $_file       = new File();
                $_file->path = $image->store(options: 'villa');
                $_file->type = File::TYPE_IMAGE;

                $villa->files()->save($_file);
            }

            foreach ($this->request->type as $t) {
                if ($this->is_edit) {
                    $villa_type = VillaTypeRepository::first(['id' => $t['id']]);
                    VillaTypeRepository::update($villa_type->id, [
                        'name'          => $t['name'],
                        'total_unit'    => $t['total_unit'],
                        'price'         => $t['price'],
                        'description'   => $t['description'],
                        'is_publish'    => $t['status'],
                    ]);

                    if (@$t['images']) {
                        foreach ($villa_type->files as $file) {
                            Storage::disk('villa')->delete($file->path);
                            $file->delete();
                        }
                    }
                } else {
                    $villa_type = VillaTypeRepository::create([
                        'villa_id'      => $villa->id,
                        'name'          => $t['name'],
                        'total_unit'    => $t['total_unit'],
                        'price'         => $t['price'],
                        'description'   => $t['description'],
                        'is_publish'    => true,
                    ]);
                }

                if ($this->is_edit) {
                    $villa_type->facilities()->sync($t['facilities']);
                } else {
                    $villa_type->facilities()->attach($t['facilities']);
                }

                foreach ($t['images'] ?? [] as $image) {
                    $_file       = new File();
                    $_file->path = $image->store(options: 'villa');
                    $_file->type = File::TYPE_IMAGE;

                    $villa_type->files()->save($_file);
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
