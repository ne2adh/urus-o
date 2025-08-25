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
            'Abaroa',
            'Pantaleón Dalence',
            'Tomás Barrón',
            'Mejillones',
            'Sebastián Pagador',
            'Sabaya',
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
            "Sabaya" => 55,
            "Ladislao Cabrera" => 64,
            "Poopó" => 89,
            "Abaroa" => 224,
            "Pantaleón Dalence" => 173,
            "Tomás Barrón" => 28,
            "Mejillones" => 10,
            "Sebastián Pagador" => 49,
            "Otro" => 0
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

        // Totales por provincia (conteo absoluto)
        $rawProv = DB::table('participantes')
            ->select('provincia', DB::raw('COUNT(*) as total'))
            ->groupBy('provincia')
            ->pluck('total', 'provincia')
            ->toArray();

        $provLabels = $provincias; // ya definido en tu controlador
        $provTotals = [];
        foreach ($provincias as $p) {
            $provTotals[] = (int) ($rawProv[$p] ?? 0);
        }

        // Sugerir tope del eje Y (redondeado)
        $provMax = !empty($provTotals) ? max($provTotals) : 0;
        $provSuggested = $provMax > 0 ? (int) (ceil($provMax / 5) * 5) : 10;

        $metaTotal    = 6000;
        $acumulado    = (int) ($totalGlobal ?? 0);
        $pendienteAbs = max(0, $metaTotal - $acumulado);
        $cumplidoPct  = $metaTotal > 0 ? round(($acumulado / $metaTotal) * 100, 2) : 0.0;
        $pendientePct = max(0, 100 - $cumplidoPct);

        $provMap = [
            "Abaroa"            => ["alias"=>"Abaroa","habilitado"=>22309,"requerido"=>224],
            "Carangas"          => ["alias"=>"Carangas","habilitado"=>5925,"requerido"=>60],
            "Cercado"           => ["alias"=>"Cercado","habilitado"=>268352,"requerido"=>4708],
            "Ladislao Cabrera"  => ["alias"=>"Ladislao Cabrera","habilitado"=>6375,"requerido"=>64],
            "Litoral"           => ["alias"=>"Litoral","habilitado"=>3429,"requerido"=>35],
            "Mejillones"        => ["alias"=>"Mejillones","habilitado"=>873,"requerido"=>10],
            "Nor Carangas"      => ["alias"=>"Nor Carangas","habilitado"=>2880,"requerido"=>29],
            "Pantaleón Dalence" => ["alias"=>"Pantaleón Dalence","habilitado"=>17279,"requerido"=>173],
            "Poopó"             => ["alias"=>"Poopó","habilitado"=>8867,"requerido"=>89],
            "Sabaya"            => ["alias"=>"Sabaya","habilitado"=>5426,"requerido"=>55],
            "Sajama"            => ["alias"=>"Sajama","habilitado"=>4928,"requerido"=>50],
            "San Pedro de Totora" => ["alias"=>"San Pedro de Totora","habilitado"=>2637,"requerido"=>27],
            "Saucari"           => ["alias"=>"Saucari","habilitado"=>3182,"requerido"=>32],
            "Sebastián Pagador" => ["alias"=>"Sebastián Pagador","habilitado"=>4883,"requerido"=>49],
            "Sur Carangas"      => ["alias"=>"Sur Carangas","habilitado"=>3111,"requerido"=>32],
            "Tomás Barrón"      => ["alias"=>"Tomás Barrón","habilitado"=>2769,"requerido"=>28],
            "Otro"              => ["alias"=>"Otro","habilitado"=>0,"requerido"=>0],
        ];

        // Conteos de registrados
        $regProv = DB::table('participantes')
            ->select('provincia', DB::raw('COUNT(*) as t'))
            ->groupBy('provincia')
            ->pluck('t','provincia')
            ->toArray();

        $tablaExcel = [];
        $totHab = $totReq = $totReg = $totRes = 0;

        foreach ($provMap as $prov => $info) {
            $hab = $info['habilitado'];
            $req = $info['requerido'];
            $reg = (int)($regProv[$prov] ?? 0);
            $res = max(0,$req - $reg);

            $tablaExcel[] = [
                'provincia'  => $prov,
                'habilitado'=> $hab,
                'requerido' => $req,
                'registrado'=> $reg,
                'restante'  => $res,
                'pend_pct'  => $req>0 ? round(($res/$req)*100,2) : 0,
            ];

            $totHab += $hab; $totReq += $req; $totReg += $reg; $totRes += $res;
        }

        $totalesExcel = [
            'habilitado'=>$totHab,
            'requerido'=>$totReq,
            'registrado'=>$totReg,
            'restante'=>$totRes,
        ];

        // dataset para gráficas de torta por provincia
        $piesExcel = [];
        foreach ($tablaExcel as $r) {
            $piesExcel[] = [
                'provincia'   => $r['provincia'],
                'registrado'  => $r['registrado'],
                'pendiente'   => max(0, $r['requerido'] - $r['registrado']),
                'requerido'   => $r['requerido'],
            ];
        }

        $totalOtro   = (int) DB::table('participantes')->where('provincia','Otro')->count();
        $totalValido = $totalGlobal - $totalOtro;

        return view('dashboard', [
            'kpis'        => [
                'totalGlobal' => $totalGlobal,
                'totalGlobal2' => (6000 - $totalGlobal),
                'totalValido' => $totalValido,
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
            'provLabels'   => $provLabels,
            'provTotals'   => $provTotals,
            'provSuggested'=> $provSuggested,
            'metaTotal'     => $metaTotal,
            'acumulado'     => $acumulado,
            'pendienteAbs'  => $pendienteAbs,
            'cumplidoPct'   => $cumplidoPct,
            'pendientePct'  => $pendientePct,
            'tablaExcel'   => $tablaExcel,
            'totalesExcel' => $totalesExcel,
            'piesExcel'    => $piesExcel,
        ]);
    }
}
