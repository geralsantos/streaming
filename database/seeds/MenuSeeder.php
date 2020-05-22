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
            'perfil' => '1,2,3,4',
            'padre_id' => 0,
            'url' => '*',
            'icon' => 'home',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('menu')->insert([
            'nombre' => 'Pantalla Principal',
            'perfil' => '1,2,3,4',
            'padre_id' => 1,
            'url' => 'dashboard',
            'icon' => 'home',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('menu')->insert([
            'nombre' => 'Stream Online',
            'perfil' => '1,2,3,4',
            'padre_id' => 0,
            'url' => 'streaming/salas',
            'icon' => '360',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),

        ]);

        DB::table('menu')->insert([
            'nombre' => 'Registrar',
            'perfil' => '1,2',
            'padre_id' => 0,
            'url' => 'registrar',
            'icon' => 'settings_applications',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('menu')->insert([
            'nombre' => 'Usuario',
            'perfil' => '1,2',
            'padre_id' => 4,
            'url' => 'registrar/usuarios',
            'icon' => 'supervisor_account',
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }
}
