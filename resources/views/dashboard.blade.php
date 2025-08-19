@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-5">
            <div class="text-lg font-semibold text-blue-500 mb-1">TOTAL META</div>
            <div class="text-3xl font-semibold">6000</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <div class="text-lg text-orange-500 mb-1">TOTAL REGISTRADOS DEL DÍA</div>
            <div class="text-3xl font-semibold">{{ number_format($kpis['totalHoy']) }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <div class="text-lg text-red-500 mb-1">TOTAL ACUMULADO</div>
            <div class="text-3xl font-semibold text-green-400">{{ number_format($kpis['totalGlobal']) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Barras: Totales por PROVINCIA (un total por provincia en eje X) --}}
        <div class="lg:col-span-full bg-white rounded-xl shadow p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Avance por provincia (%)</h3>
                <span class="text-xs text-gray-500">0% – 100%</span>
            </div>
            <div class="h-72">
                <canvas id="bars"></canvas>
            </div>
        </div>
        {{-- Tablas: Provincias (totales) y Municipios (totales) --}}
        <div class="lg:col-span-3 grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Donut + Metas por provincia --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold mb-3">Avance de meta (a la fecha)</h3>
                <div class="flex items-center gap-6">
                    <div class="w-40 h-40">
                        <canvas id="donut"></canvas>
                    </div>
                    <div class="flex-1">
                        <ul class="text-sm space-y-2">
                            <li class="flex items-center justify-between">
                                <span class="text-gray-600">Cumplido</span>
                                <span class="font-semibold">{{ number_format($donut[0], 2) }}%</span>
                            </li>
                            <li class="flex items-center justify-between">
                                <span class="text-gray-600">Pendiente</span>
                                <span class="font-semibold">{{ number_format($donut[1], 2) }}%</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="text-sm font-semibold mb-2">Provincias (Meta vs Actual)</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs sm:text-sm">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="px-3 py-2 text-left">Provincia</th>
                                    <th class="px-3 py-2 text-right">Meta</th>
                                    <th class="px-3 py-2 text-right">Actual</th>
                                    <th class="px-3 py-2 text-right">% Cumpl.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProv as $p)
                                    <tr class="border-t">
                                        <td class="px-3 py-2">{{ $p['provincia'] }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($p['meta']) }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($p['actual']) }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($p['pct'], 2) }}%</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-3 py-6 text-center text-gray-500">Sin datos</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-semibold">Registros por usuario</h3>
                    <span class="text-xs text-gray-500">Hoy y acumulado</span>
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
        </div>

    </div>

    <script>
        // ===== BARRAS: Totales por provincia =====
        (function initBars() {
            const el = document.getElementById('bars');
            const prev = Chart.getChart(el);
            if (prev) prev.destroy();
            const ctx = el.getContext('2d');

            const labels = @json($provLabels);
            const data = @json($provPct); // % 0–100

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Avance (%)',
                        data
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
                                callback: (v) => v + '%'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` ${ctx.parsed.y}%`
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

            const donutData = @json($donut);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Cumplido', 'Pendiente'],
                    datasets: [{
                        data: donutData
                    }]
                },
                options: {
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        })();
    </script>
@endsection
