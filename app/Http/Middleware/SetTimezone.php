<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;

class SetTimezone
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Intentamos obtener la timezone de la BD, si falla usamos la de config/app.php
            $timezone = Setting::where('key', 'timezone')->value('value');
            
            if ($timezone) {
                // Configuramos la zona horaria de la aplicación dinámicamente
                Config::set('app.timezone', $timezone);
                date_default_timezone_set($timezone);
            }
        } catch (\Exception $e) {
            // Si la base de datos no está lista aún, no hacemos nada
        }

        return $next($request);
    }
}