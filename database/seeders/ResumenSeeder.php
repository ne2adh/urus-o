<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resumen;
use App\Models\User;
use Carbon\Carbon;

class ResumenSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id')->all();
        if (empty($users)) return;

        $base = Carbon::today();
        for ($i = 1; $i <= 20; $i++) {
            $uid = $users[array_rand($users)];
            $fecha = $base->copy()->subDays($i);
            Resumen::create([
                'fecha' => $fecha->format('Y-m-d'),
                'numero_dia' => (int)$fecha->format('z') + 1,
                'user_id' => $uid,
                'provincia' => 'Prov '.$i,
                'municipio' => 'Mun '.$i,
                'circunscripcion' => ($i % 5) + 1,

                'total_dia' => 10 + $i,
                'total_dia_prov' => 5 + $i,
                'total_dia_mun' => 3 + $i,
                'total_dia_circ' => 2 + $i,

                'acum_user' => 100 + $i,
                'acum_user_prov' => 80 + $i,
                'acum_user_mun' => 50 + $i,
                'acum_user_circ' => 30 + $i,
                'porc_meta_user' => 50 + ($i % 50),

                'created_by' => $uid,
                'updated_by' => $uid,
            ]);
        }
    }
}
