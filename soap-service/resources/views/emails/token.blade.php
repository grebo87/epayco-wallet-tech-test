<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token de Pago - ePayco</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 10px;
        }
        .token-container {
            background-color: #f8f9fa;
            border: 2px dashed #3498db;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .token {
            font-size: 36px;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 5px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
        }
        .details {
            background-color: #e9ecef;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 5px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #495057;
        }
        .detail-value {
            color: #2c3e50;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-title {
            font-weight: bold;
            color: #856404;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">ePayco Wallet</div>
            <h1>Token de Pago</h1>
        </div>

        <p>Estimado usuario,</p>
        <p>Se ha generado un token para confirmar tu pago. Por favor, utiliza el siguiente código:</p>

        <div class="token-container">
            <h2>Tu Token de Pago</h2>
            <div class="token">{{ $token }}</div>
            <p><small>Este token expirará en 10 minutos</small></p>
        </div>

        <div class="details">
            <h3>Detalles de la Transacción</h3>
            <div class="detail-row">
                <span class="detail-label">Monto:</span>
                <span class="detail-value">${{ number_format($amount, 2) }}</span>
            </div>
            {{-- <div class="detail-row">
                <span class="detail-label">ID de Sesión:</span>
                <span class="detail-value">{{ $sessionId }}</span>
            </div> --}}
            <div class="detail-row">
                <span class="detail-label">Fecha y Hora:</span>
                <span class="detail-value">{{ now()->format('d/m/Y H:i:s') }}</span>
            </div>
        </div>

        <div class="warning">
            <div class="warning-title">⚠️ Importante:</div>
            <ul>
                <li>Nunca compartas este token con otras personas</li>
                <li>El token es de un solo uso</li>
                <li>Si no realizaste esta operación, ignora este correo</li>
            </ul>
        </div>

        <p>Si tienes alguna pregunta, no dudes en contactar nuestro equipo de soporte.</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} ePayco Wallet. Todos los derechos reservados.</p>
            <p>Este es un mensaje automático, por favor no responder.</p>
        </div>
    </div>
</body>
</html>
