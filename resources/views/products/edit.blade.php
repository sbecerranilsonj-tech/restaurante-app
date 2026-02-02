@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
    <div>
        <h2 class="fw-bold text-dark mb-0">Editar Producto</h2>
        <p class="text-muted mb-0">Gestiona detalles y receta</p>
    </div>
</div>

<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="row g-4">
    @csrf
    @method('PUT')

    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold text-primary mb-3">Información General</h6>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Nombre del Producto</label>
                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Categoría</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Código de Barras</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-upc-scan"></i></span>
                            <input type="text" name="barcode" class="form-control" value="{{ $product->barcode }}" placeholder="Escanear...">
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Precio (S/)</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Stock Actual (Lectura)</label>
                        <input type="text" class="form-control bg-light" value="{{ $product->stock }}" readonly>
                        <small class="text-muted" style="font-size: 0.7rem;">Si tiene receta, el stock dependerá de los insumos.</small>
                    </div>
                </div>

                <div class="form-check form-switch mb-4 bg-light p-3 rounded border">
                    <input class="form-check-input" type="checkbox" name="is_saleable" id="saleableCheck" {{ $product->is_saleable ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold ms-2" for="saleableCheck">Disponible para Venta en POS</label>
                    <div class="small text-muted ms-1 mt-1">Si desmarcas esto, el producto servirá como <strong>Insumo</strong> (para recetas) pero no aparecerá en el menú de ventas.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Imagen</label>
                    <div class="d-flex align-items-center gap-3">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" class="rounded border" width="60" height="60" style="object-fit: cover;">
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-primary text-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-basket me-2"></i>Receta / Insumos</h6>
                <button type="button" class="btn btn-sm btn-light text-primary fw-bold" onclick="addIngredient()">+ Agregar</button>
            </div>
            <div class="card-body p-3 bg-light overflow-auto" style="max-height: 400px;">
                <p class="small text-muted mb-3">Selecciona los insumos que componen este plato. Al venderlo, se descontarán automáticamente.</p>
                
                <div id="ingredients-list">
                    @foreach($product->ingredients as $ingredient)
                        <div class="input-group mb-2" id="row-{{ $ingredient->id }}">
                            <span class="input-group-text bg-white border-end-0" style="font-size: 0.8rem; width: 60%;">{{ $ingredient->name }}</span>
                            <input type="number" step="0.01" name="ingredients[{{ $ingredient->id }}]" value="{{ $ingredient->pivot->quantity }}" class="form-control form-control-sm text-center border-start-0" placeholder="Cant.">
                            <button type="button" class="btn btn-sm btn-outline-danger bg-white" onclick="document.getElementById('row-{{ $ingredient->id }}').remove()"><i class="bi bi-x"></i></button>
                        </div>
                    @endforeach
                </div>

                <div class="d-none" id="ingredient-select-template">
                    <div class="input-group mb-2 ingredient-row">
                        <select class="form-select form-select-sm" style="width: 55%;" onchange="setIngredientName(this)">
                            <option value="">- Insumo -</option>
                            @foreach($ingredients as $ing)
                                <option value="{{ $ing->id }}">{{ $ing->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" step="0.01" class="form-control form-control-sm text-center" placeholder="Cant.">
                        <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
                    </div>
                </div>

            </div>
            <div class="card-footer bg-white border-0 text-end">
                <button type="submit" class="btn btn-success fw-bold w-100">
                    <i class="bi bi-check-lg me-2"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    function addIngredient() {
        let container = document.getElementById('ingredients-list');
        let template = document.getElementById('ingredient-select-template').innerHTML;
        let div = document.createElement('div');
        div.innerHTML = template;
        container.appendChild(div.firstElementChild);
    }

    function setIngredientName(select) {
        let row = select.parentElement;
        let inputQty = row.querySelector('input[type="number"]');
        if (select.value) {
            inputQty.name = "ingredients[" + select.value + "]";
            inputQty.required = true;
        }
    }
</script>
@endsection