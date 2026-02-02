@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger shadow-lg">
                <div class="card-header bg-danger text-white fw-bold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> ZONA DE PELIGRO: Reinicio del Sistema
                </div>
                <div class="card-body text-center p-5">
                    
                    <h3 class="fw-bold text-danger mb-3">¿Estás listo para inaugurar?</h3>
                    <p class="text-muted fs-5">Esta acción eliminará todos los datos de prueba para dejar el sistema listo para producción.</p>

                    <div class="alert alert-warning d-inline-block text-start mt-3">
                        <strong>Se eliminarán permanentemente:</strong>
                        <ul class="mb-0 mt-2">
                            <li>{{ $counts['orders'] }} Ventas y pedidos registrados.</li>
                            <li>{{ $counts['reservations'] }} Reservas de mesa.</li>
                            <li>{{ $counts['logs'] }} Movimientos de Kardex.</li>
                            <li>El <strong>Stock</strong> de todos los productos volverá a <strong>0</strong>.</li>
                        </ul>
                    </div>
                    
                    <p class="mt-3 small text-muted">
                        * Tus Usuarios, Productos, Mesas, Clientes y Configuración <strong>NO</strong> se borrarán.
                    </p>

                    <hr>

                    <form action="{{ route('system.reset') }}" method="POST" class="mt-4" onsubmit="return confirm('¿ESTÁS 100% SEGURO? NO HAY VUELTA ATRÁS.');">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ingresa tu contraseña para confirmar:</label>
                            <input type="password" name="password" class="form-control w-50 mx-auto text-center" required placeholder="Tu contraseña actual">
                        </div>
                        <button type="submit" class="btn btn-danger btn-lg fw-bold w-100">
                            <i class="bi bi-trash3-fill"></i> BORRAR TODO E INICIAR DE CERO
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection