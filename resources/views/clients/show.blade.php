@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('clients.index') }}" class="btn btn-light border shadow-sm me-3"><i class="bi bi-arrow-left"></i> Volver</a>
        <div>
            <h2 class="fw-bold text-dark mb-0">Perfil de Cliente</h2>
            <p class="text-muted mb-0">Historial y preferencias</p>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <div class="mb-3 mt-2 position-relative d-inline-block">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold border" style="width: 100px; height: 100px; font-size: 3rem;">
                            {{ substr($client->name, 0, 1) }}
                        </div>
                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-{{ $badgeColor }} border border-white shadow-sm" style="font-size: 0.9rem;">
                            {{ $rank }}
                        </span>
                    </div>

                    <h4 class="fw-bold mb-1">{{ $client->name }}</h4>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-geo-alt-fill text-danger"></i> {{ $client->address ?? 'Sin dirección' }}
                    </p>
                    
                    <div class="row g-2 text-start bg-light p-3 rounded mx-1">
                        <div class="col-12"><small class="text-muted fw-bold">DOCUMENTO:</small> <div class="float-end">{{ $client->document_number ?? '-' }}</div></div>
                        <div class="col-12 border-top my-1"></div>
                        <div class="col-12"><small class="text-muted fw-bold">TELÉFONO:</small> <div class="float-end">{{ $client->phone ?? '-' }}</div></div>
                        <div class="col-12 border-top my-1"></div>
                        <div class="col-12"><small class="text-muted fw-bold">EMAIL:</small> <div class="float-end text-break small">{{ $client->email ?? '-' }}</div></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-primary text-white h-100">
                        <div class="card-body">
                            <small class="opacity-75 fw-bold text-uppercase">Total Gastado</small>
                            <h3 class="fw-bold mb-0">S/ {{ number_format($totalSpent, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-success text-white h-100">
                        <div class="card-body">
                            <small class="opacity-75 fw-bold text-uppercase">Visitas</small>
                            <h3 class="fw-bold mb-0">{{ $visitCount }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-warning text-dark h-100">
                        <div class="card-body">
                            <small class="opacity-75 fw-bold text-uppercase">Plato Favorito</small>
                            <h5 class="fw-bold mb-0 text-truncate" title="{{ $favoriteProduct }}">{{ $favoriteProduct }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Historial de Pedidos</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="ps-4">Fecha</th>
                                    <th>Folio</th>
                                    <th>Mesa</th>
                                    <th class="text-end pe-4">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td class="ps-4">
                                            {{ $order->created_at->format('d/m/Y') }} <br>
                                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span></td>
                                        <td>{{ $order->table->name ?? 'Barra' }}</td>
                                        <td class="text-end pe-4 fw-bold">S/ {{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <a href="{{ route('sales.ticket', $order->id) }}" target="_blank" class="btn btn-sm btn-link text-dark"><i class="bi bi-printer"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">Aún no tiene pedidos registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection