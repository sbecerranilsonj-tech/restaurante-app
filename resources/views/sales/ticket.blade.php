<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $order->id }}</title>
    <style>
        /* CONFIGURACIÓN EXACTA PARA IMPRESORA TÉRMICA */
        @page {
            margin: 0;
            padding: 0;
            size: auto;
        }
        body {
            font-family: 'Courier New', Courier, monospace; /* Fuente monoespaciada para alinear columnas */
            font-size: 12px;
            margin: 0;
            padding: 5px;
            width: 78mm; /* Ancho estándar de papel térmico (80mm menos márgenes de seguridad) */
            color: #000;
            background: #fff;
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        .header { margin-bottom: 10px; border-bottom: 1px dashed #000; padding-bottom: 5px; }
        .footer { margin-top: 10px; border-top: 1px dashed #000; padding-top: 5px; font-size: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        td, th { vertical-align: top; padding: 2px 0; }
        
        /* Columnas */
        .qty { width: 10%; text-align: left; }
        .desc { width: 60%; text-align: left; }
        .price { width: 30%; text-align: right; }
        
        .totals { margin-top: 10px; border-top: 1px solid #000; padding-top: 5px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 2px; }
        
        /* Ocultar botones al imprimir */
        @media print {
            .no-print { display: none; }
            body { margin: 0; padding: 0; }
        }
    </style>
</head>
<body onload="window.print()"> <div class="no-print" style="position: fixed; top: 0; right: 0; padding: 10px; background: white; border: 1px solid #ccc; z-index: 1000;">
        <button onclick="window.print()" style="padding: 10px 20px; font-weight: bold; cursor: pointer;">🖨️ Imprimir</button>
    </div>

    <div class="header text-center">
        @if(isset($settings['company_logo']))
            <img src="{{ asset('storage/'.$settings['company_logo']) }}" style="max-width: 50px; filter: grayscale(100%); margin-bottom: 5px;">
            <br>
        @endif
        
        <div class="fw-bold fs-5 uppercase" style="font-size: 14px;">{{ $settings['company_name'] ?? 'MI RESTAURANTE' }}</div>
        <div>{{ $settings['company_address'] ?? 'Dirección del Local' }}</div>
        <div>Tel: {{ $settings['company_phone'] ?? '---' }}</div>
        <div style="margin-top: 5px;">{{ now()->format('d/m/Y H:i') }}</div>
        <div>TICKET: #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
        
        @if($order->client_name && $order->client_name != 'Público')
            <div style="margin-top: 3px; font-weight: bold;">Cli: {{ Str::limit($order->client_name, 20) }}</div>
        @endif
        
        <div class="fw-bold" style="margin-top: 3px; font-size: 13px;">MESA: {{ $order->table->name ?? 'BARRA' }}</div>
    </div>

    <table>
        <thead>
            <tr style="border-bottom: 1px solid #000;">
                <th class="qty">C.</th>
                <th class="desc">DESCRIPCION</th>
                <th class="price">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->details as $detail)
                <tr>
                    <td class="qty">{{ $detail->quantity }}</td>
                    <td class="desc">
                        {{ $detail->product->name }}
                        @if($detail->note) 
                            <br><i style="font-size: 10px;">({{ $detail->note }})</i> 
                        @endif
                    </td>
                    <td class="price">{{ number_format($detail->quantity * $detail->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="row">
            <span>Subtotal:</span>
            <span>{{ $settings['currency_symbol'] ?? 'S/' }} {{ number_format($order->total - ($order->tip ?? 0) + ($order->discount ?? 0), 2) }}</span>
        </div>
        
        @if($order->discount > 0)
        <div class="row">
            <span>Descuento:</span>
            <span>-{{ number_format($order->discount, 2) }}</span>
        </div>
        @endif

        @if($order->tip > 0)
        <div class="row">
            <span>Propina:</span>
            <span>{{ number_format($order->tip, 2) }}</span>
        </div>
        @endif

        <div class="row fw-bold" style="font-size: 16px; margin-top: 5px; border-top: 1px dashed #000; padding-top: 5px;">
            <span>TOTAL A PAGAR:</span>
            <span>{{ $settings['currency_symbol'] ?? 'S/' }} {{ number_format($order->total, 2) }}</span>
        </div>
        
        <div class="row" style="margin-top: 5px; font-size: 10px;">
            <span>F. PAGO: {{ strtoupper($order->payment_method) }}</span>
        </div>
        <div class="row" style="font-size: 10px;">
            <span>RECIBIDO: {{ number_format($order->received_amount, 2) }}</span>
            <span>VUELTO: {{ number_format($order->change_amount, 2) }}</span>
        </div>
    </div>

    <div class="footer text-center">
        {{ $settings['ticket_footer'] ?? '¡Gracias por su preferencia!' }}
        <br><br>
        .
    </div>

</body>
</html>