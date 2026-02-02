@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0"><i class="bi bi-people-fill me-2"></i>Personal</h2>
            <p class="text-muted mb-0">Gestiona los accesos y roles de tu equipo</p>
        </div>
        <button class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="bi bi-person-plus-fill me-2"></i> Nuevo Usuario
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Usuario</th>
                            <th>Rol / Cargo</th>
                            <th>Email de Acceso</th>
                            <th>Fecha Registro</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3 {{ $user->role == 'admin' ? 'bg-danger' : ($user->role == 'cashier' ? 'bg-primary' : 'bg-success') }}">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                            @if($user->id === Auth::id())
                                                <span class="badge bg-light text-dark border" style="font-size: 0.6rem;">TÚ</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->role == 'admin')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2">Administrador</span>
                                    @elseif($user->role == 'cashier')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2">Cajero</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2">Mozo / Staff</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td class="text-muted small">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-link text-dark p-0 me-3" onclick="editUser({{ $user }})" title="Editar">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </button>
                                    
                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar a {{ $user->name }}?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-link text-danger p-0 border-0 bg-transparent" title="Eliminar">
                                                <i class="bi bi-trash fs-5"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('users.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Registrar Personal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre Completo</label>
                    <input type="text" name="name" class="form-control" required placeholder="Ej: Juan Pérez">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Correo Electrónico (Login)</label>
                    <input type="email" name="email" class="form-control" required placeholder="juan@restaurante.com">
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Rol / Permisos</label>
                        <select name="role" class="form-select">
                            <option value="waiter">Mozo (Solo Pedidos)</option>
                            <option value="cashier">Cajero (Cobros y Gastos)</option>
                            <option value="admin">Administrador (Total)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary fw-bold">Guardar Usuario</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editUserForm" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold text-dark">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control" required>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label fw-bold">Nueva Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="Dejar vacío para no cambiar">
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-bold">Rol</label>
                        <select name="role" id="edit_role" class="form-select">
                            <option value="waiter">Mozo</option>
                            <option value="cashier">Cajero</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning fw-bold">Actualizar Datos</button>
            </div>
        </form>
    </div>
</div>

<style>
    .avatar-circle {
        width: 40px; height: 40px;
        border-radius: 50%;
        color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 18px;
        text-transform: uppercase;
    }
</style>

<script>
    function editUser(user) {
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('editUserForm').action = "{{ url('/users') }}/" + user.id;
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    }
</script>
@endsection