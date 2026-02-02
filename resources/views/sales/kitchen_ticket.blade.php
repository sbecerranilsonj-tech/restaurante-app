<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comanda #{{ $order->id }}</title>
    <style>
        @page { margin: 0; padding: 0; }
        body {
            font-family: 'Arial', sans-serif;
            width: 80mm;
            margin: 0 auto;
            padding: 5mm;
            color: #000;
        }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .title { font-size: 20px; font-weight: 900; text-transform: uppercase; display: block; }
        .meta { font-size: 14px; margin-top: 5px; }
        
        .item { 
            display: flex; 
            margin-bottom: 10px; 
            border-bottom: 1px dashed #444; 
            padding-bottom: 5px;
        }
        .qty-box {
            width: 15%;
            font-size: 22px;
            font-weight: 900;
            text-align: center;
            padding-top: 2px;
        }
        .desc-box {
            width: 85%;
            padding-left: 5px;
        }
        .prod-name {
            font-size: 16px;
            font-weight: 700;
            display: block;
        }
        .note {
            font-size: 14px;
            background-color: #000;
            color: #fff;
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
            display: inline-block;
            margin-top: 2px;
        }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; }
        
        /* Ocultar botón al imprimir */
        @media print { .no-print { display: none; } }
        .print-btn {
            position: fixed; bottom: 20px; right: 20px;
            background: #000; color: #fff; border: none; padding: 15px;
            font-size: 20px; border-radius: 50%; cursor: pointer;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn no-print">🖨</button>

    <div class="header">
        <span class="title">ORDEN DE COCINA</span>
        <div class="meta">
            <strong>MESA: {{ $order->table->name }}</strong><br>
            <span>Mozo: {{ $order->user->name }}</span><br>
            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    @foreach($order->details as $detail)
    <div class="item">
        <div class="qty-box">{{ $detail->quantity }}</div>
        <div class="desc-box">
            <span class="prod-name">{{ $detail->product->name }}</span>
            @if($detail->note)
                <span class="note">⚠️ {{ $detail->note }}</span>
            @endif
        </div>
    </div>
    @endforeach

    <div class="footer">
        --- FIN DE ORDEN #{{ $order->id }} ---
    </div>

    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>