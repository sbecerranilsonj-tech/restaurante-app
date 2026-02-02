<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Restaurante POS</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        .login-header {
            background: #0d6efd;
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 15px 15px 0 0;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <div class="login-header">
            <h3 class="fw-bold mb-0">Restaurante POS</h3>
            <small>Sistema de Gestión Profesional</small>
        </div>
        <div class="card-body p-4">
            <h5 class="text-center text-muted mb-4">Bienvenido de nuevo</h5>
            
            <form action="{{ route('login.perform') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label text-secondary fw-bold">Correo Electrónico</label>
                    <input type="email" class="form-control py-2" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@admin.com">
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label text-secondary fw-bold">Contraseña</label>
                    <input type="password" class="form-control py-2" id="password" name="password" required placeholder="******">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm">
                        INGRESAR AL SISTEMA
                    </button>
                </div>
            </form>
        </div>
        <div class="card-footer bg-white text-center py-3 border-0 rounded-bottom">
            <small class="text-muted">Desarrollado con Laravel & Bootstrap</small>
        </div>
    </div>

</body>
</html>