<div class="flex-grow-1 overflow-auto p-2">
    @if($order && $order->details->count() > 0)
        <div class="table-responsive" style="overflow-x: hidden;">
            <table class="table table-borderless align-middle mb-0" style="width: 100%; table-layout: fixed;">
                <thead class="text-muted small border-bottom">
                    <tr>
                        <th style="width: 30px;"></th> <th>PROD.</th>
                        <th class="text-center" style="width: 85px;">CANT.</th>
                        <th class="text-end" style="width: 65px;">TOT.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->details as $detail)
                        <tr class="border-bottom">
                            
                            <td class="px-0 align-middle">
                                <button class="btn btn-sm text-danger p-0" onclick="removeItem({{ $detail->id }})" title="Eliminar">
                                    <i class="bi bi-x-circle-fill fs-5"></i>
                                </button>
                            </td>

                            <td class="align-middle px-1">
                                <div class="fw-bold text-dark text-truncate" style="width: 100%; font-size: 0.85rem;" title="{{ $detail->product->name }}">
                                    {{ $detail->product->name }}
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-1" style="font-size: 0.7rem;">
                                        {{ $currency ?? 'S/' }}{{ number_format($detail->price, 2) }}
                                    </small>
                                    @if($detail->note)
                                        <i class="bi bi-chat-square-text-fill text-warning" style="font-size: 0.7rem;" title="{{ $detail->note }}"></i>
                                    @endif
                                    <a href="javascript:void(0)" class="ms-1 text-decoration-none text-primary" 
                                       data-bs-toggle="modal" data-bs-target="#noteModal"
                                       data-detail-id="{{ $detail->id }}" 
                                       data-note-content="{{ $detail->note }}">
                                       <i class="bi bi-pencil" style="font-size: 0.7rem;"></i>
                                    </a>
                                </div>
                            </td>

                            <td class="align-middle px-0">
                                <div class="input-group input-group-sm flex-nowrap">
                                    <button class="btn btn-outline-secondary px-1 py-0" style="font-size: 0.8rem;" onclick="updateQty({{ $detail->id }}, {{ $detail->quantity - 1 }})">-</button>
                                    <input type="text" class="form-control text-center px-0 py-0 fw-bold bg-white border-secondary" 
                                           value="{{ $detail->quantity }}" readonly style="font-size: 0.85rem;">
                                    <button class="btn btn-outline-primary px-1 py-0" style="font-size: 0.8rem;" onclick="updateQty({{ $detail->id }}, {{ $detail->quantity + 1 }})">+</button>
                                </div>
                            </td>

                            <td class="text-end align-middle fw-bold text-dark px-0" style="font-size: 0.9rem;">
                                {{ number_format($detail->quantity * $detail->price, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted opacity-50">
            <i class="bi bi-basket3 display-1 mb-3"></i>
            <p class="small text-center m-0">Cuenta vacía</p>
        </div>
    @endif
</div>

<div class="bg-white border-top p-3 flex-shrink-0">
    @if($order)
        <input type="hidden" id="cartTotalValue" value="{{ number_format($order->total + ($order->tip ?? 0) - ($order->discount ?? 0), 2, '.', '') }}">

        <div class="row mb-1" style="font-size: 0.8rem;">
            <div class="col-6 text-muted">Subtotal:</div>
            <div class="col-6 text-end">{{ number_format($order->total, 2) }}</div>
        </div>
        
        @if($order->discount > 0)
            <div class="row mb-1 text-danger fw-bold" style="font-size: 0.8rem;">
                <div class="col-6">Descuento:</div>
                <div class="col-6 text-end">-{{ number_format($order->discount, 2) }}</div>
            </div>
        @endif

        @if($order->tip > 0)
            <div class="row mb-1 text-success fw-bold" style="font-size: 0.8rem;">
                <div class="col-6">Propina:</div>
                <div class="col-6 text-end">+{{ number_format($order->tip, 2) }}</div>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-2 mt-2 border-top pt-2">
            <h5 class="mb-0 fw-bold text-dark fs-6">TOTAL:</h5>
            <h3 class="mb-0 fw-bold text-primary fs-4">
                {{ $currency ?? 'S/' }}{{ number_format($order->total + ($order->tip ?? 0) - ($order->discount ?? 0), 2) }}
            </h3>
        </div>

        <div class="row g-1 mb-2">
            <div class="col-4">
                <button class="btn btn-light w-100 border text-muted btn-sm fw-bold py-2" data-bs-toggle="modal" data-bs-target="#optionsModal" title="Opciones">
                    <i class="bi bi-sliders"></i> <span style="font-size: 0.7rem;">Opc.</span>
                </button>
            </div>
            <div class="col-4">
                <a href="{{ route('pos.split.content', $order->id) }}" class="btn btn-light w-100 border text-muted btn-sm fw-bold py-2" title="Dividir">
                    <i class="bi bi-scissors"></i> <span style="font-size: 0.7rem;">Div.</span>
                </a>
            </div>
            <div class="col-4">
                <a href="{{ route('pos.precheck', $order->id) }}" target="_blank" class="btn btn-light w-100 border text-muted btn-sm fw-bold py-2" title="Pre-cuenta">
                    <i class="bi bi-receipt"></i> <span style="font-size: 0.7rem;">Pre</span>
                </a>
            </div>
        </div>

        <div class="d-grid">
            <button class="btn btn-success fw-bold py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                <i class="bi bi-cash-coin me-2"></i> COBRAR
            </button>
        </div>
    @endif
</div>