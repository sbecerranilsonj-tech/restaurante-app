@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('products.index') }}" class="text-decoration-none text-muted small"><i class="bi bi-arrow-left"></i> Volver a Productos</a>
            <h2 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2"></i>Kardex de Movimientos</h2>
            <p class="text-muted mb-0">Auditoría detallada de entradas y salidas de stock</p>
        </div>
        <div class="bg-white p-2 border rounded shadow-sm">
            <span class="badge bg-success me-1">Entrada</span> Compra/Reposición
            <span class="badge bg-danger ms-2 me-1">Salida</span> Venta/Merma
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Fecha / Hora</th>
                            <th>Producto</th>
                            <th>Tipo Movimiento</th>
                            <th>Motivo / Nota</th>
                            <th>Usuario</th>
                            <th class="text-center">Cant.</th>
                            <th class="text-center">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="ps-4 text-muted small">
                                    {{ $log->created_at->format('d/m/Y') }}<br>
                                    {{ $log->created_at->format('H:i:s') }}
                                </td>
                                <td class="fw-bold">{{ $log->product->name }}</td>
                                <td>
                                    @if($log->type == 'sale')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">VENTA POS</span>
                                    @elseif($log->type == 'entry')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success">ENTRADA</span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">AJUSTE / MERMA</span>
                                    @endif
                                </td>
                                <td class="text-muted fst-italic small">{{ $log->note ?? '-' }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-dark border me-2" style="width: 25px; height: 25px; font-size: 10px;">
                                            {{ substr($log->user->name ?? 'S', 0, 1) }}
                                        </div>
                                        <small>{{ $log->user->name ?? 'Sistema' }}</small>
                                    </div>
                                </td>
                                <td class="text-center fw-bold {{ $log->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $log->quantity > 0 ? '+' : '' }}{{ $log->quantity }}
                                </td>
                                <td class="text-center fw-bold bg-light">
                                    {{ $log->new_stock }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-box-seam fs-1 opacity-50"></i><br>
                                    No hay movimientos registrados aún.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection