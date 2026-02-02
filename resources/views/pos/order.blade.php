@extends('layouts.app')

@section('content')
<div class="container-fluid p-0 d-flex flex-column" style="height: calc(100vh - 20px); overflow: hidden;">
    
    <div class="d-flex justify-content-between align-items-center bg-white border-bottom px-4 py-2 mb-3 shadow-sm flex-shrink-0">
        <div class="d-flex align-items-center">
            <a href="{{ route('pos.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <div>
                <h5 class="fw-bold mb-0 text-primary">Mesa: {{ $table->name }}</h5>
                <small class="text-muted">Zona: {{ $table->area->name }}</small>
            </div>
        </div>
        
        <div class="d-flex align-items-center gap-2">
            @if($order)
                <button type="button" class="btn btn-outline-primary btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#moveTableModal">
                    <i class="bi bi-arrow-left-right me-1"></i> Mover Mesa
                </button>
            @endif
            <div class="vr mx-2"></div>
            <span class="badge bg-light text-dark border p-2">
                <i class="bi bi-person-fill me-1"></i> {{ auth()->user()->name }}
            </span>
        </div>
    </div>

    <div class="row g-0 flex-grow-1 overflow-hidden">
        
        <div class="col-md-2 bg-light border-end overflow-auto h-100 pb-5">
            <div class="list-group list-group-flush">
                <button onclick="filterProducts('all')" class="list-group-item list-group-item-action active text-center py-3 category-btn" id="cat-btn-all">
                    <i class="bi bi-grid-fill d-block fs-4 mb-1"></i> Todo
                </button>
                @foreach($categories as $category)
                    <button onclick="filterProducts('cat-{{ $category->id }}')" class="list-group-item list-group-item-action text-center py-3 category-btn" id="cat-btn-{{ $category->id }}">
                        @if($category->image)
                            <img src="{{ asset('storage/'.$category->image) }}" class="rounded mb-1" width="40" height="40" style="object-fit: cover;">
                        @else
                            <i class="bi bi-tag d-block fs-4 mb-1"></i>
                        @endif
                        <span class="d-block small fw-bold lh-sm">{{ $category->name }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="col-md-7 bg-white overflow-auto h-100 px-3 pb-5" id="products-container">
            <div class="sticky-top bg-white pt-3 pb-2 mb-2" style="z-index: 10;">
                <div class="input-group input-group-lg shadow-sm">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-upc-scan"></i></span>
                    <input type="text" id="barcodeInput" class="form-control border-start-0" placeholder="Escanear código..." autofocus autocomplete="off">
                </div>
            </div>

            <div class="row row-cols-2 row-cols-lg-3 row-cols-xl-4 g-3 pb-5">
                @foreach($categories as $category)
                    @foreach($category->products as $product)
                        <div class="col product-item cat-{{ $category->id }}">
                            <div class="card h-100 border-0 shadow-sm product-card" onclick="addToOrder({{ $product->id }})" style="cursor: pointer; transition: transform 0.1s;">
                                <div class="position-relative">
                                    @if($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top" style="height: 120px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex justify-content-center align-items-center" style="height: 120px;">
                                            <i class="bi bi-cup-straw fs-1 text-muted opacity-25"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-dark opacity-75">{{ $currency ?? 'S/' }}{{ number_format($product->price, 0) }}</span>
                                    </div>

                                    @if(!is_null($product->stock))
                                        <div class="position-absolute bottom-0 start-0 m-2">
                                            <span class="badge {{ $product->stock <= 5 ? 'bg-danger' : 'bg-success' }} border border-white shadow-sm">
                                                Stock: {{ $product->stock }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body p-2 text-center">
                                    <h6 class="card-title fs-6 mb-0 text-truncate">{{ $product->name }}</h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        <div class="col-md-3 bg-white border-start h-100 d-flex flex-column">
            <div class="p-3 bg-light border-bottom flex-shrink-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-cart"></i> Cuenta Actual</h6>
            </div>
            <div id="cart-container" class="flex-grow-1 d-flex flex-column overflow-hidden">
                @include('pos.partials.cart', ['order' => $order])
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="noteModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2 bg-warning">
                <h6 class="modal-title fw-bold text-dark">Nota Cocina</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="noteDetailId">
                <textarea id="noteText" class="form-control" rows="3"></textarea>
            </div>
            <div class="modal-footer p-1">
                <button type="button" class="btn btn-warning w-100 btn-sm text-dark fw-bold" onclick="saveNote()">Guardar Nota</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="moveTableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2 bg-info text-white">
                <h6 class="modal-title fw-bold">Mover Mesa</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            @if($order)
                <form action="{{ route('pos.move', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label class="form-label small text-muted">Destino:</label>
                        <select name="target_table_id" class="form-select" required>
                            <option value="" selected disabled>-- Elegir Mesa --</option>
                            @foreach($freeTables as $ft)
                                <option value="{{ $ft->id }}">{{ $ft->name }} ({{ $ft->area->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer p-1">
                        <button type="submit" class="btn btn-info w-100 btn-sm text-white fw-bold">Confirmar</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="optionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light py-2">
                <h6 class="modal-title fw-bold">Ajustes</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Descuento Global</label>
                    <input type="number" step="0.01" id="inputDiscount" class="form-control" value="{{ $order ? $order->discount : 0 }}" onclick="this.select()">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Propina</label>
                    <input type="number" step="0.01" id="inputTip" class="form-control" value="{{ $order ? $order->tip : 0 }}" onclick="this.select()">
                </div>
            </div>
            <div class="modal-footer p-1">
                <button type="button" class="btn btn-primary w-100 btn-sm fw-bold" onclick="applyOptions()">Aplicar Cambios</button>
            </div>
        </div>
    </div>
</div>

@if($order)
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('pos.checkout', $order->id) }}" method="POST" class="modal-content border-0 shadow-lg">
            @csrf
            <div class="modal-header bg-success text-white py-2">
                <h6 class="modal-title fw-bold">Cobrar Venta</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">CLIENTE</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" list="clientsList" id="clientSearchInput" placeholder="Buscar..." oninput="searchClient(this)" autocomplete="off">
                        <button class="btn btn-light border" type="button" onclick="document.getElementById('clientSearchInput').value=''; searchClient({value:''})"><i class="bi bi-x"></i></button>
                    </div>
                    <datalist id="clientsList">
                        @foreach($clients as $client)
                            <option value="{{ $client->name }}" data-id="{{ $client->id }}" data-document="{{ $client->document_number }}"></option>
                        @endforeach
                    </datalist>
                    <input type="hidden" name="client_id" id="clientId">
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-8"><input type="text" name="client_document" id="clientDoc" class="form-control bg-light" placeholder="RUC/DNI" readonly></div>
                    <div class="col-4"><select name="document_type" class="form-select fw-bold"><option value="Ticket">Ticket</option><option value="Boleta">Boleta</option><option value="Factura">Factura</option></select></div>
                </div>
                <div class="mb-3 text-center">
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="payment_method" id="payCash" value="cash" checked onclick="toggleCashInput(true)">
                        <label class="btn btn-outline-success fw-bold" for="payCash">Efectivo</label>
                        <input type="radio" class="btn-check" name="payment_method" id="payCard" value="card" onclick="toggleCashInput(false)">
                        <label class="btn btn-outline-primary fw-bold" for="payCard">Tarjeta</label>
                    </div>
                </div>
                <div id="cashInputGroup">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Recibido</label>
                        <input type="number" step="0.01" name="received_amount" id="receivedAmount" class="form-control text-center fw-bold fs-4 text-success" 
                               value="{{ number_format($order->total + ($order->tip ?? 0) - ($order->discount ?? 0), 2, '.', '') }}" 
                               oninput="calculateChange()" onclick="this.select()">
                    </div>
                    <div class="d-flex justify-content-between">
                        <small>Cambio:</small>
                        <h4 class="fw-bold mb-0 text-secondary" id="changeAmount">0.00</h4>
                    </div>
                </div>
                <input type="hidden" id="hiddenTotal" value="{{ number_format($order->total + ($order->tip ?? 0) - ($order->discount ?? 0), 2, '.', '') }}">
            </div>
            <div class="modal-footer p-2 bg-light">
                <button type="submit" class="btn btn-success w-100 btn-lg fw-bold">CONFIRMAR PAGO</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
    const tableId = {{ $table->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Escáner
    const barcodeInput = document.getElementById('barcodeInput');
    if(barcodeInput) {
        barcodeInput.focus();
        barcodeInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); 
                let code = barcodeInput.value.trim();
                if(code.length > 0) addByBarcode(code); 
            }
        });
    }

    // AJAX
    window.addByBarcode = function(code) {
        fetch(`{{ url('/pos/order') }}/${tableId}/barcode`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ barcode: code })
        }).then(r => r.text()).then(html => {
            document.getElementById('cart-container').innerHTML = html;
            barcodeInput.value = ''; barcodeInput.focus();
            updateCheckoutTotal();
        });
    };

    window.addToOrder = function(productId) {
        fetch(`{{ url('/pos/order') }}/${tableId}/add`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ product_id: productId })
        }).then(r => r.text()).then(html => {
            document.getElementById('cart-container').innerHTML = html;
            updateCheckoutTotal();
        });
    };

    window.updateQty = function(id, qty) {
        if(qty < 1 && !confirm('¿Eliminar producto?')) return;
        fetch(`{{ url('/pos/detail') }}/${id}/update`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ quantity: qty })
        }).then(r => r.text()).then(html => {
            document.getElementById('cart-container').innerHTML = html;
            updateCheckoutTotal();
        });
    };

    window.removeItem = function(id) {
        fetch(`{{ url('/pos/detail') }}/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken }
        }).then(r => r.text()).then(html => {
            document.getElementById('cart-container').innerHTML = html;
            updateCheckoutTotal();
        });
    };

    window.applyOptions = function() {
        var discount = document.getElementById('inputDiscount').value;
        var tip = document.getElementById('inputTip').value;
        var modal = bootstrap.Modal.getInstance(document.getElementById('optionsModal'));
        modal.hide();

        fetch(`{{ url('/pos/order') }}/{{ $order ? $order->id : 0 }}/discount`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ discount: discount, tip: tip })
        }).then(r => r.text()).then(html => {
            document.getElementById('cart-container').innerHTML = html;
            updateCheckoutTotal();
        });
    };

    // Utils
    window.filterProducts = function(cat) {
        document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById(cat === 'all' ? 'cat-btn-all' : 'cat-btn-' + cat.replace('cat-', '')).classList.add('active');
        document.querySelectorAll('.product-item').forEach(item => {
            item.style.display = (cat === 'all' || item.classList.contains(cat)) ? 'block' : 'none';
        });
    };

    window.updateCheckoutTotal = function() {
        setTimeout(() => {
            var newTotal = document.getElementById('cartTotalValue') ? document.getElementById('cartTotalValue').value : 0;
            var hiddenInput = document.getElementById('hiddenTotal');
            var receivedInput = document.getElementById('receivedAmount');
            if(hiddenInput) hiddenInput.value = newTotal;
            if(receivedInput) receivedInput.value = newTotal;
        }, 500);
    };

    // Modal Notas
    var noteModalEl = document.getElementById('noteModal');
    if(noteModalEl){
        noteModalEl.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            document.getElementById('noteDetailId').value = button.getAttribute('data-detail-id');
            document.getElementById('noteText').value = button.getAttribute('data-note-content') || '';
            setTimeout(() => document.getElementById('noteText').focus(), 500);
        });
    }
    window.saveNote = function() {
        var detailId = document.getElementById('noteDetailId').value;
        var note = document.getElementById('noteText').value;
        var modal = bootstrap.Modal.getInstance(document.getElementById('noteModal'));
        modal.hide();
        fetch(`{{ url('/pos/detail') }}/${detailId}/note`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ note: note })
        }).then(r => r.text()).then(html => document.getElementById('cart-container').innerHTML = html);
    };

    // Cobro
    window.toggleCashInput = function(show) { document.getElementById('cashInputGroup').style.display = show ? 'block' : 'none'; }
    window.calculateChange = function() {
        var total = parseFloat(document.getElementById('hiddenTotal').value) || 0;
        var received = parseFloat(document.getElementById('receivedAmount').value) || 0;
        var el = document.getElementById('changeAmount');
        if(el) el.innerText = (received - total).toFixed(2);
    }
    window.searchClient = function(input) {
        var list = document.getElementById('clientsList');
        if(!list || input.value === '') { document.getElementById('clientId').value=''; document.getElementById('clientDoc').value=''; return; }
        for(var i=0; i<list.options.length; i++) {
            if(list.options[i].value === input.value) {
                document.getElementById('clientId').value = list.options[i].getAttribute('data-id');
                document.getElementById('clientDoc').value = list.options[i].getAttribute('data-document');
                break;
            }
        }
    }
</script>

<style>
    .product-card:active { transform: scale(0.95); background-color: #f8f9fa; }
</style>
@endsection