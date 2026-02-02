@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-0"><i class="bi bi-box-seam-fill me-2"></i>Inventario de Productos</h2>
        <p class="text-muted mb-0">Gestión de carta y existencias</p>
    </div>
    <div>
        <a href="{{ route('inventory.logs') }}" class="btn btn-dark me-2">
            <i class="bi bi-clock-history me-1"></i> Ver Kardex
        </a>
        <a href="{{ route('products.create') }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Nuevo Producto
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 text-uppercase text-muted small fw-bold">Producto</th>
                        <th class="text-uppercase text-muted small fw-bold">Categoría</th>
                        <th class="text-uppercase text-muted small fw-bold">Precio</th>
                        <th class="text-uppercase text-muted small fw-bold">Stock</th>
                        <th class="text-center text-uppercase text-muted small fw-bold">Estado</th>
                        <th class="text-end pe-4 text-uppercase text-muted small fw-bold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}" class="rounded me-3 border" width="48" height="48" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center border text-muted" style="width: 48px; height: 48px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-dark">{{ $product->name }}</div>
                                        
                                        @if($product->barcode)
                                            <small class="text-muted d-block" style="font-size: 0.75rem;">
                                                <i class="bi bi-upc-scan me-1"></i>{{ $product->barcode }}
                                            </small>
                                        @endif

                                        @if(!$product->is_saleable)
                                            <span class="badge bg-secondary" style="font-size: 0.65rem;"><i class="bi bi-eye-slash me-1"></i>Solo Insumo</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $product->category->name }}</span>
                            </td>
                            <td class="fw-bold text-primary">S/ {{ number_format($product->price, 2) }}</td>
                            <td>
                                @if(is_null($product->stock))
                                    <span class="text-muted small">--</span>
                                @elseif($product->stock <= 5)
                                    <span class="badge bg-warning text-dark border border-warning">Bajo: {{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-light text-success border border-success fw-bold">{{ $product->stock }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('products.toggle', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $product->is_active ? 'btn-outline-success' : 'btn-outline-secondary' }} rounded-pill px-3 fw-bold" style="font-size: 0.75rem;">
                                        {{ $product->is_active ? 'ACTIVO' : 'INACTIVO' }}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#adjustStock{{ $product->id }}" title="Ajustar Stock">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </button>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="if(confirm('¿Eliminar producto?')) document.getElementById('del-{{$product->id}}').submit()" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <form id="del-{{$product->id}}" action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
                                
                                <div class="modal fade" id="adjustStock{{ $product->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-sm modal-dialog-centered">
                                        <form action="{{ route('products.adjust', $product->id) }}" method="POST" class="modal-content">
                                            @csrf
                                            <div class="modal-header py-2 bg-light">
                                                <h6 class="modal-title fw-bold">Ajustar Stock</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <p class="small mb-2">Producto: <strong>{{ $product->name }}</strong></p>
                                                <div class="input-group mb-3">
                                                    <select name="type" class="form-select" style="max-width: 90px;">
                                                        <option value="add">➕</option>
                                                        <option value="sub">➖</option>
                                                    </select>
                                                    <input type="number" name="quantity" class="form-control" placeholder="Cantidad" required min="1">
                                                </div>
                                            </div>
                                            <div class="modal-footer p-1">
                                                <button class="btn btn-primary w-100 btn-sm">Guardar Ajuste</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">No hay productos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0 py-3">
        {{ $products->links() }}
    </div>
</div>
@endsection