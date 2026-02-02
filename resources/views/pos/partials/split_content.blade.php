@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-0"><i class="bi bi-arrows-angle-expand me-2"></i> Dividir Cuenta</h2>
                    <p class="text-muted mb-0">Selecciona los items que deseas cobrar por separado</p>
                </div>
                <a href="{{ route('pos.order', $order->table_id) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">Mesa: {{ $order->table->name ?? 'Mesa' }} - Orden #{{ $order->id }}</h5>
                </div>
                
                <form action="{{ route('pos.split', $order->id) }}" method="POST">
                    @csrf
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" style="width: 50px;">
                                            <input type="checkbox" class="form-check-input" id="checkAll" onclick="toggleAll(this)">
                                        </th>
                                        <th>Producto</th>
                                        <th class="text-center">Cant.</th>
                                        <th class="text-end pe-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->details as $detail)
                                    <tr>
                                        <td class="ps-4">
                                            <input type="checkbox" name="selected_items[]" value="{{ $detail->id }}" class="form-check-input item-check" data-price="{{ $detail->price * $detail->quantity }}" onchange="calculateSplitTotal()">
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $detail->product->name }}</div>
                                            @if($detail->note) <small class="text-muted">{{ $detail->note }}</small> @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border">{{ $detail->quantity }}</span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold">
                                            {{ number_format($detail->price * $detail->quantity, 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-light p-4">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Método de Pago para esta parte:</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="payment_method" id="splitCash" value="cash" checked>
                                    <label class="btn btn-outline-success" for="splitCash"><i class="bi bi-cash"></i> Efectivo</label>

                                    <input type="radio" class="btn-check" name="payment_method" id="splitCard" value="card">
                                    <label class="btn btn-outline-primary" for="splitCard"><i class="bi bi-credit-card"></i> Tarjeta</label>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted text-uppercase fw-bold">Total a Cobrar</small>
                                <h2 class="display-6 fw-bold text-primary mb-0" id="splitTotalDisplay">0.00</h2>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold" id="btnSplit" disabled>
                                <i class="bi bi-check-circle-fill me-2"></i> Cobrar Selección
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function toggleAll(source) {
        checkboxes = document.querySelectorAll('.item-check');
        for(var i=0, n=checkboxes.length;i<n;i++) {
            checkboxes[i].checked = source.checked;
        }
        calculateSplitTotal();
    }

    function calculateSplitTotal() {
        let total = 0;
        let checks = document.querySelectorAll('.item-check:checked');
        let btn = document.getElementById('btnSplit');

        checks.forEach((checkbox) => {
            total += parseFloat(checkbox.getAttribute('data-price'));
        });

        document.getElementById('splitTotalDisplay').innerText = total.toFixed(2);
        
        // Habilitar botón solo si hay items seleccionados
        if(total > 0) {
            btn.disabled = false;
            btn.innerHTML = `<i class="bi bi-check-circle-fill me-2"></i> Cobrar ${total.toFixed(2)}`;
        } else {
            btn.disabled = true;
            btn.innerHTML = `<i class="bi bi-check-circle-fill me-2"></i> Cobrar Selección`;
        }
    }
</script>
@endsection