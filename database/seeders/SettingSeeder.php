<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'company_name' => 'Mi Restaurante VIP',
            'company_address' => 'Av. Gastronómica 123, Lima',
            'company_phone' => '(01) 555-9999',
            'ticket_footer' => '¡Gracias por su preferencia! Vuelva pronto.',
            'currency_symbol' => '$',
        ];

        foreach ($data as $key => $value) {
            Setting::create(['key' => $key, 'value' => $value]);
        }
    }
}