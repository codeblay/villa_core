<?php

namespace Database\Seeders;

use App\Models\Villa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VillaSeeder extends Seeder
{
    const DATA = [
        [
            'name'                      => 'Villa Merapi',
            'city_id'                   => 223,
            'price'                     => 800_000,
            'description'               => "Nunc semper neque eget nisi mattis laoreet. Nullam nec magna lacinia, ornare massa ut, ultrices metus. Maecenas vitae nisi ut ex convallis tristique quis et ligula. Nam sagittis dictum venenatis. Ut dui mauris, scelerisque in libero quis, egestas porta neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Maecenas faucibus venenatis elit. Quisque malesuada magna sodales, posuere risus eget, rhoncus eros. Etiam hendrerit ante turpis, ac tincidunt orci sodales et. Integer ut lobortis ipsum. Ut eu interdum leo, sit amet congue massa. Pellentesque interdum tellus quis neque ullamcorper fringilla. Nullam elementum elementum nibh sed iaculis. Etiam varius massa erat, vel accumsan justo euismod eu.",
            'facilities'                => [1, 2, 3]
        ],
        [
            'name'                      => 'Villa Ubud',
            'city_id'                   => 278,
            'price'                     => 1_000_000,
            'description'               => "Quisque porttitor hendrerit sapien, sit amet tincidunt augue fermentum ac. Proin ultrices consequat mauris eget venenatis. Nullam urna ex, convallis vitae est posuere, dignissim bibendum justo. Aenean non nisi et est commodo ornare vel eget nisl. Vivamus faucibus velit ut metus dictum lacinia. Aenean hendrerit ac odio eget feugiat. In in eleifend nisi, a fermentum dolor. Morbi tempus leo purus, eu sagittis augue consectetur id. Aliquam congue dapibus sollicitudin.",
            'facilities'                => [3, 4, 5]
        ],
        [
            'name'                      => 'Villa Ungaran',
            'city_id'                   => 188,
            'price'                     => 500_000,
            'description'               => "Mauris bibendum enim ut elit sodales, vitae eleifend tortor vestibulum. Duis nec lobortis erat, nec venenatis lorem. Donec lobortis ex eget iaculis facilisis. Aliquam mattis id odio sit amet commodo. Nulla facilisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus semper turpis lectus, id egestas leo vehicula sit amet.",
            'facilities'                => [2, 4, 6]
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            foreach (self::DATA as $data) {
                Villa::query()->create([
                    'name'          => $data['name'],
                    'seller_id'     => 1,
                    'city_id'       => $data['city_id'],
                    'description'   => $data['description'],
                    'price'         => $data['price'],
                    'is_publish'    => true,
                ])->facilities()->attach($data['facilities']);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
