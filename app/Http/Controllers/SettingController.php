<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        
        // Lista de zonas horarias comunes en Latinoamérica para el select
        $timezones = [
            'America/Lima' => '(UTC-05:00) Lima, Bogotá, Quito',
            'America/Caracas' => '(UTC-04:00) Caracas',
            'America/La_Paz' => '(UTC-04:00) La Paz',
            'America/Santiago' => '(UTC-03:00) Santiago',
            'America/Argentina/Buenos_Aires' => '(UTC-03:00) Buenos Aires',
            'America/Montevideo' => '(UTC-03:00) Montevideo',
            'America/Mexico_City' => '(UTC-06:00) Ciudad de México',
            'America/Tijuana' => '(UTC-08:00) Tijuana',
            'America/New_York' => '(UTC-05:00) Hora del Este (EE.UU.)',
            'Europe/Madrid' => '(UTC+01:00) Madrid',
            'UTC' => '(UTC+00:00) Tiempo Universal Coordinado'
        ];

        return view('settings.index', compact('settings', 'timezones'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'company_logo']);

        // 1. Guardar textos (Nombre, Timezone, Moneda, etc)
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // 2. Guardar Logo
        if ($request->hasFile('company_logo')) {
            $request->validate(['company_logo' => 'image|max:2048']);
            $oldLogo = Setting::where('key', 'company_logo')->value('value');
            if ($oldLogo) Storage::disk('public')->delete($oldLogo);
            $path = $request->file('company_logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'company_logo'], ['value' => $path]);
        }

        return redirect()->back()->with('success', 'Configuración actualizada. La hora se ajustará en la próxima carga.');
    }
}