@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
            <h2 class="fw-bold text-dark mb-0">Nuevo Producto</h2>
        </div>

        <div class="card border-0 shadow-sm">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="card-body p-4">
                @csrf
                
                <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">Información General</h6>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Nombre del Producto <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-lg" placeholder="Ej: Lomo Saltado" required autofocus>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Categoría <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="" selected disabled>-- Seleccionar --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Código de Barras (Opcional)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-upc-scan"></i></span>
                            <input type="text" name="barcode" class="form-control" placeholder="Escanear o escribir...">
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Precio de Venta <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">S/</span>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">Stock Inicial</label>
                        <input type="number" name="stock" class="form-control" placeholder="0">
                        <small class="text-muted" style="font-size: 0.75rem;">Se creará un registro de entrada en el Kardex.</small>
                    </div>
                </div>

                <div class="form-check form-switch mb-4 bg-light p-3 rounded border">
                    <input class="form-check-input" type="checkbox" name="is_saleable" id="saleableCheck" checked>
                    <label class="form-check-label fw-bold ms-2" for="saleableCheck">Disponible para Venta en POS</label>
                    <div class="small text-muted ms-1 mt-1">Si desmarcas esto, el producto servirá como <strong>Insumo</strong> (para recetas) pero no aparecerá en el menú de ventas.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">Imagen (Opcional)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary fw-bold px-4 py-2">
                        <i class="bi bi-save me-2"></i> Guardar Producto
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection