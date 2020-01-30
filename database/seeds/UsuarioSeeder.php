<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuario')->truncate();
        
        DB::table('usuario')->insert([
            'perfil_id' => 1,
            'usuario' => 'geral',
            'password' => Hash::make('123'),
            'usuario_creacion' => 1,
            'usuario_edicion' => 1,
            'fecha_creacion' => now()->format('Y-m-d H:i:s'),
            'fecha_modificacion' => now()->format('Y-m-d H:i:s'),
        ]);
        
    }
}
