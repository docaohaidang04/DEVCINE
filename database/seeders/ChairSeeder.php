<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chair;

class ChairSeeder extends Seeder
{
    public function run()
    {
        $rows = ['A', 'B', 'C', 'D', 'E', 'F'];
        $idRoom = 1;

        foreach ($rows as $row) {
            for ($column = 1; $column <= 10; $column++) {
                Chair::create([
                    'id_room' => $idRoom,
                    'chair_name' => $row . $column,
                    'chair_status' => 'available',
                    'column' => $column,
                    'row' => $row,
                    'price' => rand(50000, 200000), 
                ]);
            }
        }
    }
}
