@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0"><i class="bi bi-grid-3x3-gap-fill me-2"></i> Diseño de Salón</h2>
            <p class="text-muted mb-0">Arrastra las mesas y guarda la distribución</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#areaModal">
                <i class="bi bi-plus-circle me-2"></i> Nueva Zona
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tableModal">
                <i class="bi bi-plus-lg me-2"></i> Nueva Mesa
            </button>
            <button class="btn btn-success fw-bold px-4 shadow" onclick="savePositions()" id="btnSave">
                <i class="bi bi-save me-2"></i> Guardar Diseño
            </button>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" id="areaTabs" role="tablist">
        @foreach($areas as $index => $area)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $index == 0 ? 'active' : '' }} fw-bold" 
                        id="tab-{{ $area->id }}" 
                        data-bs-toggle="tab" 
                        data-bs-target="#area-{{ $area->id }}" 
                        type="button" role="tab">
                    {{ $area->name }}
                </button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content" id="areaTabsContent">
        @foreach($areas as $index => $area)
            <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="area-{{ $area->id }}" role="tabpanel">
                
                <div class="d-flex justify-content-between align-items-center mb-2 bg-white p-2 border rounded">
                    <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Arrastra las mesas y luego presiona <b>Guardar Diseño</b>.</small>
                    <form action="{{ route('tables.destroyArea', $area->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta zona y sus mesas?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger border-0">Eliminar Zona</button>
                    </form>
                </div>

                <div class="salon-canvas bg-white border rounded shadow-sm position-relative" style="height: 600px; background-image: radial-gradient(#dee2e6 1px, transparent 1px); background-size: 20px 20px; overflow: hidden;">
                    @foreach($area->tables as $table)
                        <div class="draggable-table position-absolute d-flex flex-column align-items-center justify-content-center bg-white border shadow-sm rounded-3"
                             id="table-{{ $table->id }}"
                             data-id="{{ $table->id }}"
                             style="width: 100px; height: 100px; 
                                    left: {{ $table->x_pos }}px; 
                                    top: {{ $table->y_pos }}px; 
                                    cursor: grab; z-index: 10;
                                    transition: box-shadow 0.2s;">
                            
                            <i class="bi bi-display fs-3 {{ $table->status == 'available' ? 'text-success' : 'text-danger' }} mb-1"></i>
                            <span class="fw-bold small text-center text-truncate w-100 px-1">{{ $table->name }}</span>
                            
                            <form action="{{ route('tables.destroyTable', $table->id) }}" method="POST" class="position-absolute top-0 end-0 m-1">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm p-0 text-danger opacity-25 hover-opacity-100" onclick="return confirm('¿Borrar mesa?')">
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="areaModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('tables.storeArea') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-light"><h5 class="modal-title fw-bold">Nueva Zona</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"><label>Nombre</label><input type="text" name="name" class="form-control" required></div>
            <div class="modal-footer"><button class="btn btn-primary w-100">Crear</button></div>
        </form>
    </div>
</div>
<div class="modal fade" id="tableModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form action="{{ route('tables.storeTable') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-light"><h5 class="modal-title fw-bold">Nueva Mesa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label>Nombre</label><input type="text" name="name" class="form-control" placeholder="Mesa 1" required></div>
                <div class="mb-3"><label>Zona</label><select name="area_id" class="form-select">@foreach($areas as $area) <option value="{{ $area->id }}">{{ $area->name }}</option> @endforeach</select></div>
            </div>
            <div class="modal-footer"><button class="btn btn-primary w-100">Crear</button></div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const draggables = document.querySelectorAll('.draggable-table');
        let activeDrag = null;
        let initialX, initialY, currentX, currentY;

        draggables.forEach(el => el.addEventListener('mousedown', dragStart));
        document.addEventListener('mouseup', dragEnd);
        document.addEventListener('mousemove', drag);

        function dragStart(e) {
            if (e.target.closest('form')) return; // No arrastrar si clickea en borrar
            activeDrag = e.currentTarget;
            
            // Obtener posición actual real
            let rect = activeDrag.getBoundingClientRect();
            let parentRect = activeDrag.parentElement.getBoundingClientRect();
            
            // Calculamos la posición relativa al contenedor
            let styleLeft = activeDrag.offsetLeft;
            let styleTop = activeDrag.offsetTop;

            initialX = e.clientX - styleLeft;
            initialY = e.clientY - styleTop;

            activeDrag.style.cursor = 'grabbing';
            activeDrag.style.zIndex = 100;
            activeDrag.classList.add('shadow-lg');
        }

        function dragEnd() {
            if(!activeDrag) return;
            activeDrag.style.cursor = 'grab';
            activeDrag.style.zIndex = 10;
            activeDrag.classList.remove('shadow-lg');
            activeDrag = null;
        }

        function drag(e) {
            if (activeDrag) {
                e.preventDefault();
                currentX = e.clientX - initialX;
                currentY = e.clientY - initialY;

                // Límites simples (evitar que salga mucho)
                if(currentX < 0) currentX = 0;
                if(currentY < 0) currentY = 0;

                activeDrag.style.left = currentX + "px";
                activeDrag.style.top = currentY + "px";
            }
        }
    });

    function savePositions() {
        let btn = document.getElementById('btnSave');
        let originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
        btn.disabled = true;

        let positions = [];
        document.querySelectorAll('.draggable-table').forEach(el => {
            positions.push({
                id: el.getAttribute('data-id'),
                x: parseInt(el.style.left.replace('px', '') || 0),
                y: parseInt(el.style.top.replace('px', '') || 0)
            });
        });

        fetch("{{ route('tables.updatePositions') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ positions: positions })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en el servidor: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if(data.status === 'success') {
                alert('¡Diseño guardado con éxito! ✅');
            } else {
                alert('Error al guardar: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('¡Ocurrió un error al guardar! \nVerifica que hayas ejecutado "php artisan migrate". \nDetalle: ' + error.message);
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>
@endsection