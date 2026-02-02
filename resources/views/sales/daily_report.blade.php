<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Caja - {{ $stats['start_date']->format('d/m/Y') }}</title>
    <style>
        @page { margin: 0; padding: 0; }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 80mm;
            margin: 0 auto;
            padding: 5mm;
            background: #fff;
            color: #000;
            font-size: 12px;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .line { border-bottom: 1px dashed #000; margin: 10px 0; }
        .header h2 { margin: 0; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 3px 0; }
        .big-total { font-size: 16px; border-top: 2px solid #000; padding-top: 5px; margin-top: 5px; }
        
        @media print { .no-print { display: none; } }
        .print-btn {
            position: fixed; bottom: 20px; right: 20px;
            background: #000; color: #fff; border: none; padding: 10px 20px;
            cursor: pointer; border-radius: 5px; font-weight: bold;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn no-print">🖨 IMPRIMIR</button>

    <div class="header text-center">
        <h2>REPORTE DE CAJA</h2>
        <p>{{ $settings['company_name'] ?? 'Mi Restaurante' }}</p>
        
        @if($stats['start_date']->equalTo($stats['end_date']))
            <p>Fecha: {{ $stats['start_date']->format('d/m/Y') }}</p>
        @else
            <p>Período: {{ $stats['start_date']->format('d/m') }} al {{ $stats['end_date']->format('d/m/Y') }}</p>
        @endif
        
        <p>Hora Impresión: {{ date('H:i') }}</p>
    </div>

    <div class="line"></div>

    <table>
        <tr>
            <td>Ventas Registradas:</td>
            <td class="text-end">{{ $stats['orders_count'] }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="fw-bold" style="margin-bottom: 5px;">INGRESOS</div>
    <table>
        <tr>
            <td>(+) Efectivo:</td>
            <td class="text-end">{{ number_format($stats['cash'], 2) }}</td>
        </tr>
        <tr>
            <td>(+) Tarjeta / Yape:</td>
            <td class="text-end">{{ number_format($stats['card'], 2) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="fw-bold" style="margin-bottom: 5px;">EGRESOS</div>
    <table>
        <tr>
            <td>(-) Gastos Operativos:</td>
            <td class="text-end">{{ number_format($stats['expenses'], 2) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="fw-bold text-center" style="margin-bottom: 5px;">BALANCE</div>
    <table>
        <tr>
            <td>Venta Total Bruta:</td>
            <td class="text-end fw-bold">{{ number_format($stats['total'], 2) }}</td>
        </tr>
        <tr>
            <td>Menos Gastos:</td>
            <td class="text-end text-danger">-{{ number_format($stats['expenses'], 2) }}</td>
        </tr>
        <tr>
            <td colspan="2"><div class="line"></div></td>
        </tr>
        <tr class="big-total">
            <td>EFECTIVO EN CAJA:</td>
            <td class="text-end fw-bold">{{ number_format($stats['balance'], 2) }}</td>
        </tr>
    </table>

    <br><br>
    <div class="text-center">
        __________________________<br>
        Firma Responsable
    </div>
    
    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>