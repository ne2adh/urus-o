<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /* if (!auth()->check() || !in_array(auth()->user()->rol, ['jefe','superadministrador'], true)) {
            return redirect()->route('participantes.create');
        } */

        $hoy = Carbon::today();

        // KPIs
        $totalGlobal = (int) DB::table('participantes')->count();
        $totalHoy    = (int) DB::table('participantes')->whereDate('created_at', $hoy)->count();

        // Catálogos
         $provincias = [
            'Cercado',
            'Sajama',
            'Sur Carangas',
            'Litoral',
            'Nor Carangas',
            'Carangas',
            'Saucari',
            'San Pedro de Totora',
            'Ladislao Cabrera',
            'Poopó',
            'Eduardo Avaroa',
            'Pantaleón Dalence',
            'Tomás Barrón',
            'Mejillones',
            'Sebastián Pagador',
            'Atahuallpa',
            'Otro'
        ];
        $municipios = [
            'Antequera',
            'Belén de Andamarca',
            'Caracollo',
            'Carangas',
            'Challapata',
            'Chipaya',
            'Choquecota',
            'Coipasa',
            'Corque',
            'Cruz de Machacamarca',
            'Curahuara de Carangas',
            'El Choro',
            'Escara',
            'Esmeralda',
            'Eucaliptus',
            'Huachacalla',
            'Huanuni',
            'Huayllamarca',
            'La Rivera',
            'Machacamarca',
            'Oruro',
            'Pampa Aullagas',
            'Pazña',
            'Poopó',
            'Sabaya',
            'Salinas de Garci Mendoza',
            'Santiago de Andamarca',
            'Santiago de Huari',
            'Santuario de Quillacas',
            'Todos Santos',
            'Toledo',
            'Totora',
            'Turco',
            'Soracachi',
            'Yunguyo de Litoral',
            'Otro'
        ];

        // Totales por provincia (conteo)
        $rawProv = DB::table('participantes')
            ->select('provincia', DB::raw('COUNT(*) as total'))
            ->groupBy('provincia')
            ->pluck('total', 'provincia')
            ->toArray();

        // Alinear al catálogo (para barras y tabla)
        $provLabels = $provincias;
        $provTotals = [];
        foreach ($provincias as $p) {
            $provTotals[] = (int) ($rawProv[$p] ?? 0);
        }

        // Totales por municipio (solo los que existan)
        $munRows = DB::table('participantes')
            ->select('municipio', DB::raw('COUNT(*) as total'))
            ->groupBy('municipio')
            ->orderByDesc('total')
            ->get();

        // ===== META por provincia (TEMP: random para demo) =====
        // Puedes reemplazar este bloque con lecturas reales desde una tabla metas_provincias
        $provMetas = [
            "Cercado" => 2683,
            "Sajama" => 50,
            "Sur Carangas" => 32,
            "Litoral" => 35,
            "Nor Carangas" => 29,
            "Carangas" => 60,
            "Saucari" => 32,
            "San Pedro de Totora" => 27,
            "Atahuallpa" => 55,
            "Ladislao Cabrera" => 64,
            "Poopó" => 89,
            "Eduardo Avaroa" => 224,
            "Pantaleón Dalence" => 173,
            "Tomás Barrón" => 28,
            "Mejillones" => 10,
            "Sebastián Pagador" => 49,
        ];

        // Construir dataset metas/actual y cálculo de % global para el donut
        $rowsMeta = [];
        $sumaMeta = 0;
        $sumaActual = 0;
        foreach ($provincias as $idx => $p) {
            $meta   = (int) $provMetas[$p];
            $actual = (int) $provTotals[$idx];
            $pct    = $meta > 0 ? round(($actual / $meta) * 100, 2) : 0.0;

            $rowsMeta[] = [
                'provincia' => $p,
                'meta'      => $meta,
                'actual'    => $actual,
                'pct'       => $pct,
            ];

            $sumaMeta   += $meta;
            $sumaActual += $actual;
        }

        // Donut (avance global vs pendiente)
        $avance = $sumaMeta > 0 ? round(($sumaActual / $sumaMeta) * 100, 2) : 0.0;
        $donut  = [ $avance, max(0, 100 - $avance) ];

        // Top provincias por actual (desc)
        usort($rowsMeta, function ($a, $b) {
            return $b['actual'] <=> $a['actual'];
        });
        $topProv = $rowsMeta; //array_slice($rowsMeta, 0, 5);

        $provPct = [];
        foreach ($provincias as $idx => $p) {
            $meta   = (int) $provMetas[$p];
            $actual = (int) $provTotals[$idx];
            $provPct[] = $meta > 0 ? round(($actual / $meta) * 100, 2) : 0.0;
        }

        // ===== NUEVO: resumen por usuario (total del día y total general) =====
        $usuariosResumen = DB::table('participantes as p')
            ->join('users as u', 'u.id', '=', 'p.user_id')
            ->select(
                'u.id',
                DB::raw("COALESCE(NULLIF(u.nombre_completo,''), u.name, u.username, CONCAT('Usuario #', u.id)) as usuario"),
                DB::raw("SUM(CASE WHEN DATE(p.created_at) = '" . $hoy->toDateString() . "' THEN 1 ELSE 0 END) as total_diario"),
                DB::raw('COUNT(*) as total_general')
            )
            ->groupBy('u.id','u.nombre_completo','u.name','u.username')
            ->orderByDesc('total_general')
            ->get();


        return view('dashboard', [
            'kpis'        => [
                'totalGlobal' => $totalGlobal,
                'totalHoy'    => $totalHoy,
            ],
            'provLabels'  => $provLabels,
            'provTotals'  => $provTotals,
            'provPct'     => $provPct,
            'donut'       => $donut,
            'usuariosResumen' => $usuariosResumen,
            'topProv'     => $topProv,      // ahora con columnas Meta/Actual/%
            'provTabla'   => array_map(function ($p, $t) {
                return ['provincia' => $p, 'total' => $t];
            }, $provLabels, $provTotals),
            'munTabla'    => $munRows,      // municipio, total
        ]);
    }
}
