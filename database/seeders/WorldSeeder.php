<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paths = [
            'sql/countries.sql',
            'sql/states.sql',
            'sql/cities.sql',
        ];

        foreach ($paths as $path) {
            $sql = file_get_contents(database_path($path));

            $sqlArr = explode("INSERT INTO", $sql);
            foreach ($sqlArr as $data) {
                if ($data) {
                    DB::unprepared("INSERT INTO ".$data);
                }
            }
        }
    }
}
