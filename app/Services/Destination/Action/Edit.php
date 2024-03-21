<?php

namespace App\Services\Destination\Action;

use App\Base\Service;
use App\Models\Destination;
use App\Models\DTO\ServiceResponse;
use App\Models\File;
use App\Repositories\DestinationRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final class Edit extends Service
{
    const CONTEXT           = "menyimpan destinasi";
    const MESSAGE_SUCCESS   = "berhasil menyimpan destinasi";
    const MESSAGE_ERROR     = "gagal menyimpan destinasi";

    const RULES_VALIDATOR = [
        'id'                        => 'required|integer',
        'name'                      => 'sometimes|string',
        'city_id'                   => 'sometimes|integer',
        'destination_category_id'   => 'sometimes|integer',
        'description'               => 'sometimes|string|min:20',
        'image'                     => 'sometimes|mimes:jpg|max:1024',
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

            $data = $validator->validated();
            unset($data['id']);
            unset($data['image']);
            DestinationRepository::update($this->request->id, $data);

            $destination    = DestinationRepository::first(['id' => $this->request->id]);
            $old_file       = $destination->file;

            if ($this->request->file('image')) {
                $this->insertNewFile($destination);
                $this->deleteOldFile($old_file);
            }

            DB::commit();
            return parent::success(self::MESSAGE_SUCCESS, Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            parent::storeLog($th, self::CONTEXT);
            return parent::error(self::MESSAGE_ERROR, Response::HTTP_BAD_REQUEST);
        }
    }

    private function insertNewFile(Destination $destination): void
    {
        $file = $this->request->file('image');

        $_file       = new File();
        $_file->path = $file->store(options: 'destination');
        $_file->type = File::TYPE_IMAGE;

        $destination->file()->save($_file);
    }

    private function deleteOldFile(File $file): void
    {
        $path = $file->path;
        $file->delete();
        Storage::disk('destination')->delete($path);
    }
}
