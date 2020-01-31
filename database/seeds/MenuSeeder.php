<?php

use Illuminate\Database\Seeder;
use App\Modules\Dashboard\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu')->truncate();
        DB::table('menu')->insert([
            'nombre' => 'INICIO',
            'codigo' => Str::random(4),
            'padre_id' => 0,
            'url' => '*',
            'icon' => 'home',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('menu')->insert([
            'nombre' => 'Pantalla Principal',
            'codigo' => Str::random(4),
            'padre_id' => 1,
            'url' => 'dashboard',
            'icon' => 'home',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('menu')->insert([
            'nombre' => 'Stream Online',
            'codigo' => Str::random(4),
            'padre_id' => 0,
            'url' => 'streaming',
            'icon' => '360',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),

        ]);

        
      
    }
}
