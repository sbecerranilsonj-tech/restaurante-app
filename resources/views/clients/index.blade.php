@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0"><i class="bi bi-people-fill me-2"></i>Cartera de Clientes</h2>
            <p class="text-muted mb-0">Gestión de relaciones y fidelización (CRM)</p>
        </div>
        <button class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createClientModal">
            <i class="bi bi-person-plus-fill me-2"></i> Nuevo Cliente
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nombre Cliente</th>
                            <th>Documento / RUC</th>
                            <th>Contacto</th>
                            <th class="text-center">Visitas</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                            <tr>
                                <td class="ps-4 fw-bold">
                                    <a href="{{ route('clients.show', $client->id) }}" class="text-decoration-none text-dark">
                                        {{ $client->name }}
                                    </a>
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $client->document_number ?? '---' }}</span></td>
                                <td class="small text-muted">
                                    @if($client->phone) <i class="bi bi-telephone"></i> {{ $client->phone }}<br> @endif
                                    @if($client->email) <i class="bi bi-envelope"></i> {{ $client->email }} @endif
                                </td>
                                <td class="text-center">
                                    @if($client->orders_count > 0)
                                        <span class="badge bg-success rounded-pill">{{ $client->orders_count }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('clients.show', $client->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Ver Perfil 360">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary me-1" onclick="editClient({{ $client }})"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar cliente?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createClientModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('clients.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Registrar Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="fw-bold form-label">Nombre Completo *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="fw-bold form-label">DNI / RUC</label>
                        <input type="text" name="document_number" class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="fw-bold form-label">Teléfono</label>
                        <input type="text" name="phone" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="fw-bold form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="fw-bold form-label">Dirección</label>
                        <input type="text" name="address" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary fw-bold">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editClientModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editClientForm" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold">Editar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Nombre</label><input type="text" name="name" id="edit_name" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Doc</label><input type="text" name="document_number" id="edit_doc" class="form-control"></div>
                <div class="mb-3"><label class="form-label">Teléfono</label><input type="text" name="phone" id="edit_phone" class="form-control"></div>
                <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" id="edit_email" class="form-control"></div>
                <div class="mb-3"><label class="form-label">Dirección</label><input type="text" name="address" id="edit_address" class="form-control"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning fw-bold">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editClient(client) {
        document.getElementById('edit_name').value = client.name;
        document.getElementById('edit_doc').value = client.document_number;
        document.getElementById('edit_phone').value = client.phone;
        document.getElementById('edit_email').value = client.email;
        document.getElementById('edit_address').value = client.address;
        document.getElementById('editClientForm').action = "{{ url('/clients') }}/" + client.id;
        new bootstrap.Modal(document.getElementById('editClientModal')).show();
    }
</script>
@endsection