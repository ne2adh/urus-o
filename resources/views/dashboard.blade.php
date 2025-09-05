@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    {{-- KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-5">
            <div class="text-lg font-semibold text-blue-500 mb-1">TOTAL META</div>
            <div class="text-3xl font-semibold">6000</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <div class="text-lg font-semibold text-yellow-500 mb-1">TOTAL RESTANTE</div>
            <div class="text-3xl font-semibold">{{ number_format($kpis['totalGlobal2']) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <div class="text-lg text-orange-500 mb-1">TOTAL DEL DÍA</div>
            <div class="text-3xl font-semibold">{{ number_format($kpis['totalHoy']) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <div class="text-lg text-red-500 mb-1">TOTAL ACUMULADO</div>
            <div class="text-3xl font-semibold text-green-400">{{ number_format($kpis['totalGlobal']) }}</div>
        </div>
        {{-- <div class="bg-white rounded-xl shadow p-5">
            <div class="text-lg text-blue-500 mb-1">TOTAL VALIDOS</div>
            <div class="text-3xl font-semibold text-blue-400">{{ number_format($kpis['totalValido']) }}</div>
        </div> --}}
    </div>

    {{-- Inyecta el plugin (una sola vez en la vista) --}}
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    {{-- NUEVA TABLA ESTILO EXCEL --}}
    <div class="col-span-full bg-white rounded-xl shadow p-6 mt-3">
        <h3 class="text-lg font-semibold mb-4">Metas por Provincia</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs sm:text-sm">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-3 py-2 text-left">PROVINCIA</th>
                        <th class="px-3 py-2 text-right">HABILITADO</th>
                        <th class="px-3 py-2 text-right">REQUERIDO</th>
                        <th class="px-3 py-2 text-right">REGISTRADO</th>
                        <th class="px-3 py-2 text-right">RESTANTE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tablaExcel as $r)
                        <tr class="border-t">
                            <td class="px-3 py-2">{{ $r['provincia'] }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($r['habilitado']) }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($r['requerido']) }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($r['registrado']) }}</td>
                            <td class="px-3 py-2 text-right">
                                <span class="px-2 py-0.5 rounded text-black bg-green-200">
                                    {{ number_format($r['restante']) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-50">
                    <tr class="font-semibold border-t">
                        <td class="px-3 py-2">TOTALES</td>
                        <td class="px-3 py-2 text-right">{{ number_format($totalesExcel['habilitado']) }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($totalesExcel['requerido']) }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($totalesExcel['registrado']) }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($totalesExcel['restante']) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    {{-- TORTAS POR PROVINCIA (reemplaza el bloque de tarjetas anterior por este) --}}
    <div class="col-span-full bg-white rounded-xl shadow p-6 mt-6">
        <h3 class="text-lg font-semibold mb-4">Avance por provincia (Requerido vs Registrado)</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            @foreach ($piesExcel as $p)
                @php
                    $req = (int) $p['requerido'];
                    $reg = (int) $p['registrado'];
                    $rest = (int) $p['pendiente'];
                    $regPct = $req > 0 ? round(($reg / $req) * 100) : 0;
                    $restPct = max(0, 100 - $regPct);
                    $slug = \Illuminate\Support\Str::slug($p['provincia'], '-');
                @endphp

                <div class="bg-slate-50 rounded-xl p-4 shadow-sm flex flex-col items-center">
                    <h4 class="font-semibold mb-2 text-center">{{ strtoupper($p['provincia']) }}</h4>

                    <div class="w-40 h-40">
                        <canvas id="pie-{{ $slug }}"></canvas>
                    </div>

                    <div class="mt-3 text-xs text-gray-700 space-y-1 text-center">
                        <div>Requerido: <span class="font-semibold">{{ number_format($req) }}</span></div>
                        <div class="flex items-center justify-center gap-2">
                            <span class="inline-block w-3 h-3 rounded-full bg-green-600"></span>
                            Registrado:
                            <span class="font-semibold">{{ number_format($reg) }}</span>
                            <span class="text-gray-500">({{ $regPct }}%)</span>
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <span class="inline-block w-3 h-3 rounded-full bg-red-600"></span>
                            Restante:
                            <span class="font-semibold">{{ number_format($rest) }}</span>
                            <span class="text-gray-500">({{ $restPct }}%)</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    {{-- PORCENTAJE: Alcance por provincia (0–100%) --}}
    <div class="col-span-full bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold">Alcance por provincia (%)</h3>
            <span class="text-xs text-gray-500">0 a 100</span>
        </div>
        <div class="h-72">
            <canvas id="barsPct"></canvas>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-3">
        {{-- Tablas: Provincias (totales) y Municipios (totales) --}}
        <div class="lg:col-span-3 grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Donut + Metas por provincia --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold mb-3">Avance de meta (a la fecha -
                    {{ \Carbon\Carbon::now()->format('d/m/Y') }})</h3>
                <div class="flex items-center gap-6">
                    <div class="w-40 h-40">
                        <canvas id="donut"></canvas>
                    </div>
                    <div class="flex-1">
                        <ul class="text-sm space-y-2">
                            <li class="flex items-center justify-between">
                                <span class="text-gray-600">Cumplido</span>
                                <span class="font-semibold">
                                    {{ number_format($cumplidoPct, 2) }}%
                                    <span
                                        class="text-xs text-gray-500">({{ number_format($acumulado) }}/{{ number_format($metaTotal) }})</span>
                                </span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="text-gray-600">Pendiente</span>
                                <span class="font-semibold">
                                    {{ number_format($pendientePct, 2) }}%
                                    <span
                                        class="text-xs text-gray-500">({{ number_format($pendienteAbs) }}/{{ number_format($metaTotal) }})</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold">Registros por usuario</h3>
                    <span class="text-xs text-gray-500">Hoy ({{ \Carbon\Carbon::now()->format('d/m/Y') }}) y acumulado
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-xs sm:text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-3 py-2 text-left">Usuario</th>
                                <th class="px-3 py-2 text-right">Total diario</th>
                                <th class="px-3 py-2 text-right">Total general</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuariosResumen as $u)
                                <tr class="border-t">
                                    <td class="px-3 py-2">{{ $u->usuario }}</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($u->total_diario) }}</td>
                                    <td class="px-3 py-2 text-right font-semibold">{{ number_format($u->total_general) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-6 text-center text-gray-500">Sin registros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-span-full bg-white rounded-xl shadow p-6 mt-6 grid grid-cols-2 lg:grid-cols-2 gap-6">
            {{-- Provincias totales --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold mb-3">Registrados por PROVINCIA (totales)</h3>
                <div class="overflow-x-auto max-h-[420px]">
                    <table class="min-w-full text-xs sm:text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-3 py-2 text-left">Provincia</th>
                                <th class="px-3 py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Ordenar desc por total
                                $provTablaOrdenada = collect($provTabla)->sortByDesc('total');
                            @endphp
                            @forelse($provTablaOrdenada as $row)
                                <tr class="border-t">
                                    <td class="px-3 py-2">{{ $row['provincia'] }}</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($row['total']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-3 py-6 text-center text-gray-500">Sin datos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Municipios totales --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold mb-3">Registrados por MUNICIPIO (totales)</h3>
                <div class="overflow-x-auto max-h-[520px]">
                    <table class="min-w-full text-xs sm:text-sm">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-3 py-2 text-left">Municipio</th>
                                <th class="px-3 py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($munTabla as $row)
                                <tr class="border-t">
                                    <td class="px-3 py-2">{{ $row->municipio ?? '-' }}</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($row->total ?? 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-3 py-6 text-center text-gray-500">Sin datos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- BARRAS: Totales por PROVINCIA (conteo absoluto) --}}
        <div class="col-span-full bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Totales por provincia</h3>
                <span class="text-xs text-gray-500">Acumulado (registros)</span>
            </div>
            <div class="h-72">
                <canvas id="bars"></canvas>
            </div>
        </div>
    </div>
    {{-- === Registros por usuario y día === --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold">Registros por usuario y día</h3>
            <span class="text-xs text-gray-500">Últimos 19 días</span>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-2 px-3">Usuario</th>
                        @foreach ($dashboard_reg_dates ?? [] as $d)
                            <th class="py-2 px-3 text-right">{{ \Carbon\Carbon::parse($d)->format('d/m/Y') }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse(($dashboard_reg_rows ?? []) as $row)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2 px-3 font-medium">{{ $row['usuario'] }}</td>
                            @foreach ($row['counts'] as $c)
                                <td class="py-2 px-3 text-right">{{ $c }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td class="py-3 px-3 text-gray-500" colspan="{{ 1 + count($dashboard_reg_dates ?? []) }}">
                                Sin datos en el periodo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{-- scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($piesExcel as $p)
                (function() {
                    const id = 'pie-{{ \Illuminate\Support\Str::slug($p['provincia'], '-') }}';
                    const el = document.getElementById(id);
                    if (!el) return;
                    const prev = Chart.getChart(el);
                    if (prev) prev.destroy();

                    new Chart(el, {
                        type: 'pie',
                        data: {
                            labels: ['Registrado', 'Restante'],
                            datasets: [{
                                data: [{{ (int) $p['registrado'] }},
                                    {{ (int) $p['pendiente'] }}
                                ],
                                backgroundColor: ['#16a34a', '#dc2626'],
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: (ctx) => {
                                            const total = {{ (int) $p['requerido'] }};
                                            const val = ctx.parsed || 0;
                                            const pct = total > 0 ? Math.round((val / total) *
                                                100) + '%' : '0%';
                                            return ` ${ctx.label}: ${val.toLocaleString()} (${pct})`;
                                        }
                                    }
                                },
                                datalabels: {
                                    color: '#fff',
                                    font: {
                                        weight: 'bold'
                                    },
                                    formatter: (value, ctx) => {
                                        const total = {{ (int) $p['requerido'] }};
                                        const pct = total > 0 ? Math.round((value / total) * 100) :
                                            0;
                                        return pct ? pct + '%' : '';
                                    }
                                }
                            }
                        },
                        plugins: [ChartDataLabels]
                    });
                })();
            @endforeach
        });
    </script>
    <script>
        // ===== BARRAS: Totales por provincia =====
        (function initBars() {
            const el = document.getElementById('bars');
            const prev = Chart.getChart(el);
            if (prev) prev.destroy();
            const ctx = el.getContext('2d');

            const labels = @json($provLabels);
            const data = @json($provTotals);
            const suggestedMax = @json($provSuggested);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Total registrados',
                        data
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: suggestedMax
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` ${Number(ctx.parsed.y).toLocaleString()}`
                            }
                        }
                    }
                }
            });
        })();

        // ===== DONUT: Avance global =====
        (function initDonut() {
            const el = document.getElementById('donut');
            const prev = Chart.getChart(el);
            if (prev) prev.destroy();
            const ctx = el.getContext('2d');

            const dataPct = [@json($cumplidoPct), @json($pendientePct)];
            const abs = {
                cumplido: @json($acumulado),
                pendiente: @json($pendienteAbs),
                meta: @json($metaTotal)
            };

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Cumplido', 'Pendiente'],
                    datasets: [{
                        data: dataPct
                    }]
                },
                options: {
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const label = context.label || '';
                                    const valPct = context.parsed;
                                    const valAbs = label === 'Cumplido' ? abs.cumplido : abs.pendiente;
                                    return ` ${label}: ${valPct}% (${valAbs.toLocaleString()}/${abs.meta.toLocaleString()})`;
                                }
                            }
                        }
                    }
                }
            });
        })();
    </script>
    <script>
        (function initBarsPct() {
            const el = document.getElementById('barsPct');
            const prev = Chart.getChart(el);
            if (prev) prev.destroy();
            const ctx = el.getContext('2d');

            const labels = @json($provLabels); // ['Cercado', ...]
            const data = @json($provPct); // [0..100 por provincia]

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Alcance (%)',
                        data,
                        backgroundColor: '#3b82f6'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: v => v + '%'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` ${ctx.parsed.y}%`
                            }
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'end',
                            color: '#111',
                            font: {
                                weight: 'bold'
                            },
                            formatter: (value) => value + '%'
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        })();
    </script>
@endsection
