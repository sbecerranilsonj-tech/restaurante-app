@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0"><i class="bi bi-bar-chart-fill me-2"></i>Reportes Gerenciales</h2>
            <p class="text-muted mb-0">Análisis detallado de rendimiento</p>
        </div>
        
        <form action="{{ route('reports.index') }}" method="GET" class="d-flex gap-2 bg-white p-2 rounded shadow-sm border">
            <div>
                <label class="small text-muted fw-bold">Desde</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
            </div>
            <div>
                <label class="small text-muted fw-bold">Hasta</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
            </div>
            <button class="btn btn-primary btn-sm fw-bold align-self-end px-3">
                <i class="bi bi-filter"></i> Analizar
            </button>
        </form>
    </div>

    <div class="row g-4 mb-4">
        
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Ingresos por Categoría</h5>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Ranking de Ventas por Personal</h5>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="waiterChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4">
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white py-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-trophy-fill me-2"></i> Top 5: Platos Estrella</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Producto</th>
                                <th class="text-center">Cant. Vendida</th>
                                <th class="text-end pe-3">Ingresos Generados</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $prod)
                                <tr>
                                    <td class="ps-3 fw-bold">{{ $prod->name }}</td>
                                    <td class="text-center"><span class="badge bg-success rounded-pill">{{ $prod->qty }}</span></td>
                                    <td class="text-end pe-3 text-success fw-bold">{{ $currency }}{{ number_format($prod->revenue, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">Sin datos</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark py-2">
                    <h6 class="fw-bold mb-0"><i class="bi bi-exclamation-circle-fill me-2"></i> Ojo: Menos Vendidos</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Producto</th>
                                <th class="text-end pe-3">Cant. Vendida</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($worstProducts as $prod)
                                <tr>
                                    <td class="ps-3 text-secondary">{{ $prod->name }}</td>
                                    <td class="text-end pe-3 fw-bold">{{ $prod->qty }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted py-3">Sin datos</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Gráfico de Categorías (Dona)
    const ctxCat = document.getElementById('categoryChart');
    if(ctxCat) {
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: @json($catLabels),
                datasets: [{
                    data: @json($catValues),
                    backgroundColor: [
                        '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6610f2', '#0dcaf0'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });
    }

    // 2. Gráfico de Mozos (Barras)
    const ctxWait = document.getElementById('waiterChart');
    if(ctxWait) {
        new Chart(ctxWait, {
            type: 'bar',
            data: {
                labels: @json($waiterLabels),
                datasets: [{
                    label: 'Ventas Totales ({{ $currency }})',
                    data: @json($waiterValues),
                    backgroundColor: 'rgba(25, 135, 84, 0.7)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });
    }
</script>
@endsection