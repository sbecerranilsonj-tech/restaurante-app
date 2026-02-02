<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ \App\Models\Setting::where('key', 'company_name')->value('value') ?? 'Mi Restaurante' }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* === VARIABLES DE ESTILO "GLAM" === */
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --dark-bg: #0f172a;
            --light-bg: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            
            --radius-xl: 24px;
            --radius-md: 16px;
            --radius-sm: 12px;
            
            --shadow-soft: 0 10px 40px -10px rgba(0,0,0,0.08);
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--light-bg); 
            color: var(--text-main);
            font-size: 0.95rem;
            overflow-x: hidden; /* Evita scroll horizontal en body */
        }

        /* === 1. SIDEBAR ESTILO APP === */
        .sidebar {
            width: 280px; 
            height: 100vh; 
            position: fixed; 
            top: 0; left: 0;
            background: var(--dark-bg); 
            color: white;
            z-index: 1050;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex; flex-direction: column;
            padding: 20px 0; /* Padding vertical, horizontal manejado dentro */
        }

        .sidebar-header {
            padding: 0 20px 20px 20px;
            display: flex; align-items: center; gap: 15px;
            flex-shrink: 0; /* No encoger header */
        }
        
        /* SCROLLBAR PERSONALIZADO PARA EL MENÚ */
        .sidebar-menu {
            padding: 10px 15px;
            flex-grow: 1; 
            overflow-y: auto; /* Habilita scroll vertical */
            scrollbar-width: thin; /* Firefox */
            scrollbar-color: rgba(255,255,255,0.2) transparent;
        }

        /* Estilos Webkit (Chrome, Edge, Safari) para el Scroll */
        .sidebar-menu::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-menu::-webkit-scrollbar-thumb {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
        }
        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }

        .logo-box {
            width: 48px; height: 48px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
        }

        .brand-name { font-weight: 800; font-size: 20px; letter-spacing: -0.5px; color: white; }

        .menu-category {
            color: #94a3b8; font-size: 0.7rem; 
            text-transform: uppercase; font-weight: 700; 
            letter-spacing: 1px; margin: 20px 10px 10px;
        }

        .nav-link {
            color: #cbd5e1;
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 5px;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex; align-items: center;
            text-decoration: none;
        }
        
        .nav-link i { font-size: 1.3rem; margin-right: 14px; opacity: 0.7; transition: opacity 0.2s; }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: white;
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.5);
        }
        .nav-link.active i { opacity: 1; }

        /* === 2. MAIN CONTENT & TOPBAR === */
        .main-content {
            margin-left: 280px;
            padding: 20px 30px;
            min-height: 100vh;
            transition: margin-left 0.3s;
            display: flex; 
            flex-direction: column;
        }

        .top-navbar {
            background: white;
            border-radius: var(--radius-md);
            padding: 10px 20px;
            margin-bottom: 25px;
            box-shadow: var(--shadow-soft);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-profile-btn {
            display: flex; align-items: center; gap: 12px;
            padding: 5px 10px; border-radius: 50px;
            transition: background 0.2s;
            cursor: pointer;
            border: 1px solid transparent;
        }
        .user-profile-btn:hover { background: #f8fafc; border-color: #e2e8f0; }

        .user-avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #f472b6, #db2777);
            color: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
        }

        /* === 3. COMPONENTES GENERALES === */
        .card {
            border: none;
            background: var(--card-bg);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-soft);
            overflow: hidden;
        }
        .card-header { background: transparent; border-bottom: 1px solid #f1f5f9; padding: 1.5rem; }
        .card-body { padding: 1.5rem; }

        .btn { padding: 0.6rem 1.4rem; border-radius: 50px; font-weight: 600; border: none; }
        .btn-primary { background: var(--primary); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); color: white; }
        .btn-primary:hover { background: var(--primary-hover); color: white; }

        .form-control, .form-select {
            background-color: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: var(--radius-sm); padding: 0.8rem 1rem;
        }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); background: white; }

        /* Estilos Tarjetas Dashboard */
        .card-solid { color: white !important; border-radius: var(--radius-xl); position: relative; overflow: hidden; }
        .bg-gradient-blue { background: linear-gradient(135deg, #3b82f6, #2563eb) !important; }
        .bg-gradient-green { background: linear-gradient(135deg, #10b981, #059669) !important; }
        .bg-gradient-red { background: linear-gradient(135deg, #ef4444, #b91c1c) !important; }
        .bg-gradient-cyan { background: linear-gradient(135deg, #06b6d4, #0891b2) !important; }
        .card-solid h2 { font-size: 2.5rem; font-weight: 800; margin: 10px 0; }
        .card-solid .icon-bg { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); font-size: 5rem; opacity: 0.15; pointer-events: none; }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); width: 100%; border-radius: 0; }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 15px; }
            .mobile-overlay {
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: rgba(15, 23, 42, 0.8); z-index: 1040;
                backdrop-filter: blur(4px); display: none;
            }
            .mobile-overlay.show { display: block; }
        }
    </style>
</head>
<body>

<div class="mobile-overlay" id="mobileOverlay" onclick="closeMenu()"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        @php $logo = \App\Models\Setting::where('key', 'company_logo')->value('value'); @endphp
        @if($logo) <img src="{{ asset('storage/'.$logo) }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 12px;">
        @else <div class="logo-box"><i class="bi bi-shop"></i></div> @endif
        <div class="brand-name ps-2 text-truncate">{{ \App\Models\Setting::where('key', 'company_name')->value('value') ?? 'Mi Restaurante' }}</div>
        <button class="btn btn-sm text-secondary d-lg-none ms-auto" onclick="closeMenu()"><i class="bi bi-x-lg"></i></button>
    </div>

    <div class="sidebar-menu">
        @php $role = Auth::user()->role; @endphp
        
        @if(in_array($role, ['admin', 'cashier']))
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
        @endif

        @if($role === 'admin')
            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line-fill"></i> Reportes
            </a>
        @endif

        <div class="menu-category">Operaciones</div>
        <a href="{{ route('pos.index') }}" class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
            <i class="bi bi-bag-check-fill"></i> Punto de Venta
        </a>
        <a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event-fill"></i> Reservas
        </a>
        @if(in_array($role, ['admin', 'cashier']))
            <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> Caja / Historial
            </a>
        @endif
        <a href="{{ route('kitchen.index') }}" class="nav-link {{ request()->routeIs('kitchen.*') ? 'active' : '' }}">
            <i class="bi bi-fire"></i> Cocina (KDS)
        </a>

        <div class="menu-category">Gestión</div>
        <a href="{{ route('clients.index') }}" class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Clientes
        </a>
        @if($role === 'admin')
            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags-fill"></i> Categorías
            </a>
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill"></i> Inventario
            </a>
            <a href="{{ route('tables.index') }}" class="nav-link {{ request()->routeIs('tables.*') ? 'active' : '' }}">
                <i class="bi bi-grid-3x3-gap-fill"></i> Mesas
            </a>
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i> Personal / Usuarios
            </a>
            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i> Configuración
            </a>
            <a href="{{ route('system.index') }}" class="nav-link {{ request()->routeIs('system.*') ? 'active' : '' }} text-danger">
                <i class="bi bi-exclamation-octagon-fill"></i> Reset
            </a>
        @endif
    </div>
</div>

<div class="main-content">
    @if(!request()->routeIs('pos.order'))
        <div class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light border d-lg-none" onclick="openMenu()"><i class="bi bi-list fs-5"></i></button>
                <h5 class="fw-bold mb-0 text-dark d-none d-sm-block">
                    @if(request()->routeIs('dashboard')) Panel de Control
                    @elseif(request()->routeIs('pos.*')) Punto de Venta
                    @elseif(request()->routeIs('products.*')) Inventario
                    @elseif(request()->routeIs('sales.*')) Caja y Movimientos
                    @elseif(request()->routeIs('users.*')) Gestión de Personal
                    @else Sistema de Restaurante @endif
                </h5>
            </div>

            <div class="dropdown">
                <div class="user-profile-btn" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-end d-none d-sm-block">
                        <div class="fw-bold text-dark small">{{ Auth::user()->name }}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">{{ ucfirst(Auth::user()->role) }}</div>
                    </div>
                    <div class="user-avatar">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <i class="bi bi-chevron-down text-muted small"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 rounded-4" style="width: 220px;">
                    <li class="px-2 py-1 text-muted small fw-bold">MI CUENTA</li>
                    <li>
                        <button class="dropdown-item rounded-3 mb-1" data-bs-toggle="modal" data-bs-target="#profileModal">
                            <i class="bi bi-person-gear me-2 text-primary"></i> Editar Perfil
                        </button>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item rounded-3 text-danger fw-bold">
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    @endif

    @if(session('success')) 
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center bg-white border-start border-5 border-success">
            <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
            <div><strong>¡Éxito!</strong> {{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div> 
    @endif
    
    @if(session('error')) 
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center bg-white border-start border-5 border-danger">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i>
            <div><strong>Error:</strong> {{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div> 
    @endif

    @yield('content')
</div>

<div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Mi Perfil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="text-muted small mb-3">Actualiza tus datos de acceso.</p>
                <form action="{{ route('users.update', Auth::user()->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Correo (Solo lectura)</label>
                        <input type="email" class="form-control bg-light" value="{{ Auth::user()->email }}" readonly>
                    </div>
                    <hr class="border-dashed">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nueva Contraseña (Opcional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para no cambiar">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-bold">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openMenu() { document.getElementById('sidebar').classList.add('show'); document.getElementById('mobileOverlay').classList.add('show'); document.body.style.overflow = 'hidden'; }
    function closeMenu() { document.getElementById('sidebar').classList.remove('show'); document.getElementById('mobileOverlay').classList.remove('show'); document.body.style.overflow = 'auto'; }
</script>
</body>
</html>