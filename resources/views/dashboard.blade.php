@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5 gap-3">
        <div class="position-relative w-100" style="max-width: 400px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" class="form-control ps-5 rounded-pill border-0 shadow-sm py-2" placeholder="Buscar en el sistema..." style="background: white;">
        </div>

        <div class="d-flex gap-3 align-items-center">
            <div class="text-end d-none d-md-block">
                <h5 class="fw-bold mb-0 text-dark">Panel de Control</h5>
                <small class="text-muted">{{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}</small>
            </div>
            <a href="{{ route('pos.index') }}" class="btn btn-primary btn-lg shadow-lg px-4 d-flex align-items-center gap-2">
                <i class="bi bi-basket2-fill"></i> <span>Nuevo Pedido</span>
            </a>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-12 col-md-4">
            <div class="card card-solid bg-gradient-blue h-100 p-3">
                <div class="card-body position-relative">
                    <h6 class="mb-2">Venta de Hoy</h6>
                    <h2 class="mb-1">{{ $currency ?? 'S/' }}{{ number_format($totalSalesToday ?? 0, 2) }}</h2>
                    <span class="badge bg-white bg-opacity-25 rounded-pill fw-normal px-3">
                        {{ $ordersCountToday ?? 0 }} órdenes
                    </span>
                    <i class="bi bi-wallet2 icon-bg"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-solid bg-gradient-green h-100 p-3">
                <div class="card-body position-relative">
                    <h6 class="mb-2">Mesas Activas</h6>
                    <h2 class="mb-1">{{ $activeTables ?? 0 }}</h2>
                    <span class="badge bg-white bg-opacity-25 rounded-pill fw-normal px-3">En servicio</span>
                    <i class="bi bi-shop-window icon-bg"></i>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-solid bg-gradient-red h-100 p-3">
                <div class="card-body position-relative">
                    <h6 class="mb-2">Alertas Stock</h6>
                    <h2 class="mb-1">{{ $lowStockProducts ?? 0 }}</h2>
                    <span class="badge bg-white bg-opacity-25 rounded-pill fw-normal px-3">Productos bajos</span>
                    <i class="bi bi-box-seam icon-bg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Estado de Salones</h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3">Disponible</span>
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3">Ocupada</span>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    @if(isset($areas) && count($areas) > 0)
                        <ul class="nav nav-pills mb-4 gap-2" id="pills-tab" role="tablist">
                            @foreach($areas as $index => $area)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index == 0 ? 'active' : '' }} rounded-pill px-4 fw-bold border" 
                                            id="pills-{{ $area->id }}-tab" data-bs-toggle="pill" 
                                            data-bs-target="#pills-{{ $area->id }}" type="button">
                                        {{ $area->name }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content">
                            @foreach($areas as $index => $area)
                                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="pills-{{ $area->id }}">
                                    <div class="row row-cols-2 row-cols-sm-3 row-cols-xl-5 g-3">
                                        @foreach($area->tables as $table)
                                            @php $isBusy = $table->orders->count() > 0; @endphp
                                            <div class="col">
                                                <a href="{{ route('pos.order', $table->id) }}" class="text-decoration-none">
                                                    <div class="card h-100 border-0 shadow-sm text-center py-3 table-hover-effect {{ $isBusy ? 'bg-danger bg-opacity-10' : 'bg-light' }}">
                                                        <div class="card-body p-2">
                                                            <div class="mb-2">
                                                                <i class="bi {{ $isBusy ? 'bi-person-workspace text-danger' : 'bi-check-circle-fill text-success' }} fs-1"></i>
                                                            </div>
                                                            <h6 class="fw-bold text-dark mb-1">{{ $table->name }}</h6>
                                                            @if($isBusy)
                                                                <span class="badge bg-danger rounded-pill">{{ $currency }}{{ number_format($table->orders->first()->total, 2) }}</span>
                                                            @else
                                                                <small class="text-muted">Libre</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">No hay áreas configuradas.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0 text-dark">🏆 Más Vendidos</h5>
                </div>
                <div class="card-body px-0">
                    <div class="list-group list-group-flush">
                        @forelse($topProducts ?? [] as $product)
                            <div class="list-group-item border-0 d-flex align-items-center px-4 py-3 hover-bg">
                                <div class="me-3 position-relative">
                                    @if($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}" class="rounded-4 shadow-sm" width="55" height="55" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded-4 d-flex align-items-center justify-content-center text-muted shadow-sm" style="width: 55px; height: 55px;"><i class="bi bi-image fs-5"></i></div>
                                    @endif
                                    <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-primary border border-white">#{{ $loop->iteration }}</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold text-dark">{{ $product->name }}</h6>
                                    <small class="text-muted">{{ $product->total_qty }} unidades</small>
                                </div>
                                <div class="fw-bold text-primary fs-5">
                                    <i class="bi bi-trophy-fill text-warning opacity-50"></i>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted">Sin datos de ventas aún.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Estilos específicos Dashboard */
    .table-hover-effect { transition: transform 0.2s, box-shadow 0.2s; }
    .table-hover-effect:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; background: white !important; }
    .hover-bg:hover { background-color: #f8fafc; }
    
    .nav-pills .nav-link { color: #64748b; background: white; border-color: #e2e8f0; }
    .nav-pills .nav-link.active { background: var(--primary); color: white; border-color: var(--primary); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
</style>

@includeIf('products.create_modal')
@endsection