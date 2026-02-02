@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Punto de Venta</h2>
            <p class="text-muted mb-0">Selecciona una mesa para comenzar</p>
        </div>
        <div class="d-flex gap-3">
            <div class="d-flex align-items-center">
                <span class="d-inline-block bg-success rounded-circle p-1 me-2" style="width: 12px; height: 12px;"></span>
                <small class="fw-bold text-muted">Disponible</small>
            </div>
            <div class="d-flex align-items-center">
                <span class="d-inline-block bg-danger rounded-circle p-1 me-2" style="width: 12px; height: 12px;"></span>
                <small class="fw-bold text-muted">Ocupada</small>
            </div>
            <div class="d-flex align-items-center">
                <span class="d-inline-block bg-warning rounded-circle p-1 me-2" style="width: 12px; height: 12px;"></span>
                <small class="fw-bold text-muted">Reservada (Hoy)</small>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs nav-pills mb-3" id="posTabs" role="tablist">
        @foreach($areas as $index => $area)
            <li class="nav-item me-2" role="presentation">
                <button class="nav-link {{ $index == 0 ? 'active' : '' }} fw-bold px-4 border" 
                        id="tab-{{ $area->id }}" 
                        data-bs-toggle="tab" 
                        data-bs-target="#area-{{ $area->id }}" 
                        type="button" role="tab">
                    {{ $area->name }}
                </button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach($areas as $index => $area)
            <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="area-{{ $area->id }}" role="tabpanel">
                
                <div class="position-relative border rounded-3 shadow-sm bg-light" 
                     style="height: 650px; overflow: auto; background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 20px 20px;">
                    
                    @foreach($area->tables as $table)
                        @php
                            $order = $table->orders->first(); // Orden activa
                            $reservations = $table->reservations; // TODAS las reservas confirmadas de hoy
                            
                            $isBusy = $order ? true : false;
                            $hasReservations = $reservations->count() > 0;
                            
                            // Estilos dinámicos
                            $cardClass = $isBusy 
                                ? 'bg-white border-danger border-2 text-danger shadow-sm' 
                                : ($hasReservations ? 'bg-white border-warning border-2 text-dark shadow-sm' : 'bg-white border-success border-2 text-success shadow-sm');
                                
                            $icon = $isBusy ? 'bi-display-fill' : 'bi-display';
                        @endphp

                        <a href="{{ route('pos.order', $table->id) }}" class="text-decoration-none text-dark">
                            <div class="pos-table-card position-absolute d-flex flex-column align-items-center justify-content-between p-2 rounded-3 {{ $cardClass }}"
                                 style="width: 110px; height: 110px; 
                                        left: {{ $table->x_pos }}px; 
                                        top: {{ $table->y_pos }}px; 
                                        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                                
                                <div class="w-100 text-center border-bottom pb-1 mb-1">
                                    <span class="fw-bold small text-uppercase" style="font-size: 0.75rem;">{{ $table->name }}</span>
                                </div>

                                <div class="flex-grow-1 d-flex align-items-center justify-content-center position-relative w-100">
                                    <i class="bi {{ $icon }} fs-1 {{ $isBusy ? 'text-danger' : 'text-secondary opacity-50' }}"></i>
                                    
                                    @if($hasReservations && !$isBusy)
                                        <div class="position-absolute top-50 start-50 translate-middle badge bg-warning text-dark border border-dark shadow-sm" 
                                             style="font-size: 0.6rem; width: 100%; white-space: normal; line-height: 1.1; z-index: 2; max-height: 60px; overflow-y: auto;">
                                            
                                            @foreach($reservations as $res)
                                                <div class="{{ !$loop->last ? 'border-bottom border-dark pb-1 mb-1' : '' }}">
                                                    <i class="bi bi-clock-fill"></i> <strong>{{ $res->reservation_time->format('H:i') }}</strong>
                                                    <br>{{ Str::limit($res->client_name, 9) }}
                                                </div>
                                            @endforeach

                                        </div>
                                    @endif
                                </div>

                                <div class="w-100 text-center mt-1">
                                    @if($isBusy)
                                        <div class="badge bg-danger w-100 py-1 shadow-sm">
                                            <small style="font-size: 0.65rem;">CONSUMO</small><br>
                                            <span class="fs-6 fw-bold">{{ $currency ?? 'S/' }}{{ number_format($order->total, 2) }}</span>
                                        </div>
                                    @else
                                        @if($hasReservations)
                                            <div class="badge bg-warning text-dark w-100 py-2 shadow-sm border border-warning">
                                                {{ $reservations->count() }} RESERVA(S)
                                            </div>
                                        @else
                                            <div class="badge bg-success w-100 py-2 shadow-sm">
                                                LIBRE
                                            </div>
                                        @endif
                                    @endif
                                </div>

                            </div>
                        </a>
                    @endforeach

                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .pos-table-card:hover {
        transform: scale(1.1) translateY(-5px); 
        z-index: 100 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        cursor: pointer;
    }
    /* Scrollbar invisible para el badge de reservas */
    .badge::-webkit-scrollbar { width: 0px; background: transparent; }
    
    .nav-pills .nav-link.active {
        background-color: #0d6efd; color: white;
        box-shadow: 0 4px 6px rgba(13, 110, 253, 0.3);
    }
    .nav-pills .nav-link { color: #495057; background-color: #fff; }
</style>
@endsection