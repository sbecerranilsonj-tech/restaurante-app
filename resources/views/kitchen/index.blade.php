@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark"><i class="bi bi-fire text-danger me-2"></i>Monitor de Cocina (KDS)</h2>
            <p class="text-muted">Pedidos pendientes de preparación</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-white text-dark border"><i class="bi bi-circle-fill text-danger me-1"></i> Pendiente</span>
            <span class="badge bg-white text-dark border"><i class="bi bi-circle-fill text-warning me-1"></i> Preparando</span>
            <div id="reloj" class="fw-bold fs-5 ms-3">00:00:00</div>
        </div>
    </div>

    <div class="row g-3">
        @forelse($orders as $order)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-header text-white d-flex justify-content-between align-items-center py-3 {{ $order->details->contains('status', 'cooking') ? 'bg-warning text-dark' : 'bg-danger' }}">
                        <div>
                            <h5 class="fw-bold mb-0">Mesa: {{ $order->table->name }}</h5>
                            <small>Folio #{{ $order->id }}</small>
                        </div>
                        <div class="text-end">
                            <i class="bi bi-clock-history"></i>
                            <span class="d-block fw-bold">{{ $order->created_at->format('H:i') }}</span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($order->details as $detail)
                                <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-secondary rounded-pill me-2 fs-6">{{ $detail->quantity }}</span>
                                            <span class="fw-bold {{ $detail->status == 'served' ? 'text-decoration-line-through text-muted' : '' }}">
                                                {{ $detail->product->name }}
                                            </span>
                                        </div>
                                        
                                        @if($detail->note)
                                            <div class="ms-5 mt-1">
                                                <span class="badge bg-warning text-dark border border-dark">
                                                    <i class="bi bi-exclamation-circle-fill"></i> {{ $detail->note }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <form action="{{ route('kitchen.update', $detail) }}" method="POST">
                                        @csrf
                                        @if($detail->status == 'pending')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Empezar
                                            </button>
                                        @elseif($detail->status == 'cooking')
                                            <button type="submit" class="btn btn-sm btn-warning">
                                                <i class="bi bi-check-lg"></i> Listo
                                            </button>
                                        @endif
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="opacity-50">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    <h2 class="mt-3 text-muted">Todo en orden, Chef.</h2>
                    <p>No hay pedidos pendientes en este momento.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<script>
    // Reloj digital simple
    setInterval(() => {
        const now = new Date();
        document.getElementById('reloj').innerText = now.toLocaleTimeString();
    }, 1000);

    // Auto-recarga de la página cada 15 segundos para ver nuevos pedidos
    setTimeout(() => {
        window.location.reload();
    }, 15000);
</script>
@endsection