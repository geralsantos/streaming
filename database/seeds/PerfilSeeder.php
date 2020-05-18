<?php

use Illuminate\Database\Seeder;

class PerfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('perfil')->truncate();
        DB::table('perfil')->insert([
            'nombre' => 'Super Administrador',
            'codigo' => 'superadmin',
            'usuario_creacion' => 1,
            'usuario_edicion' => 1,
            'fecha_creacion' => now()->format('Y-m-d H:i:s'),
            'fecha_modificacion' => now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('perfil')->insert([
            'nombre' => 'Técnico',
            'codigo' => 'tecnico',
            'usuario_creacion' => 1,
            'usuario_edicion' => 1,
            'fecha_creacion' => now()->format('Y-m-d H:i:s'),
            'fecha_modificacion' => now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('perfil')->insert([
            'nombre' => 'Cliente',
            'codigo' => 'cliente',
            'usuario_creacion' => 1,
            'usuario_edicion' => 1,
            'fecha_creacion' => now()->format('Y-m-d H:i:s'),
            'fecha_modificacion' => now()->format('Y-m-d H:i:s'),
        ]);
    }
}
