# ePayco Wallet SOAP Service

Servicio SOAP para la gestión de billetera electrónica ePayco que permite registrar clientes, consultar saldos, recargar billeteras y procesar pagos con tokens de seguridad.

## Arquitectura

Este servicio implementa una arquitectura SOA (Service-Oriented Architecture) utilizando:
- **Laravel Framework** como base
- **SOAP** para la comunicación entre servicios
- **Colas (Queue System)** para envío asíncrono de emails
- **Transacciones DB** para consistencia de datos
- **Enums** para códigos de respuesta estandarizados

## Métodos del Servicio SOAP

### 1. registerClient
Registra un nuevo cliente en el sistema.

**Parámetros:**
- `document` (string): Documento de identidad del cliente
- `name` (string): Nombre completo del cliente
- `email` (string): Correo electrónico
- `phone` (string): Teléfono

**Respuesta:**
```json
{
  "success": true,
  "code_error": "00",
  "message": "Client registered",
  "data": {
    "client_id": 123
  }
}
```

### 2. checkBalance
Consulta el saldo de un cliente.

**Parámetros:**
- `document` (string): Documento de identidad
- `phone` (string): Teléfono

**Respuesta:**
```json
{
  "success": true,
  "code_error": "00",
  "data": {
    "balance": 1500.50
  }
}
```

### 3. rechargeWallet
Recarga la billetera de un cliente.

**Parámetros:**
- `document` (string): Documento de identidad
- `phone` (string): Teléfono
- `amount` (float): Monto a recargar

**Respuesta:**
```json
{
  "success": true,
  "code_error": "00",
  "data": {
    "balance": 2500.50
  }
}
```

### 4. pay
Inicia un proceso de pago generando un token enviado por email.

**Parámetros:**
- `document` (string): Documento de identidad
- `phone` (string): Teléfono
- `amount` (float): Monto a pagar

**Respuesta:**
```json
{
  "success": true,
  "code_error": "00",
  "data": {
    "session_id": "uuid-de-sesion"
  }
}
```

### 5. confirmPayment
Confirma un pago usando el token recibido por email.

**Parámetros:**
- `idSession` (string): ID de sesión recibido en `pay()`
- `token` (string): Token de 6 dígitos recibido por email

**Respuesta:**
```json
{
  "success": true,
  "code_error": "00",
  "data": {
    "balance": 1000.00
  }
}
```

## Códigos de Respuesta

| Código | Descripción | Significado |
|--------|-------------|-------------|
| 00 | SUCCESS | Operación exitosa |
| 01 | VALIDATION_ERROR | Error de validación de datos |
| 02 | CLIENT_NOT_FOUND | Cliente no encontrado |
| 03 | CLIENT_ALREADY_EXISTS | Cliente ya existe |
| 04 | INSUFFICIENT_BALANCE | Saldo insuficiente |
| 05 | SESSION_NOT_FOUND | Sesión no encontrada |
| 06 | TOKEN_EXPIRED | Token expirado (10 minutos) |
| 07 | INTERNAL_ERROR | Error interno del servidor |
| 08 | TRANSACTION_NOT_FOUND | Transacción no encontrada |
| 09 | PAYMENT_ALREADY_CONFIRMED | Pago ya confirmado |
| 10 | TOKEN_INVALID | Token inválido |

## Instalación y Configuración

### Prerrequisitos
- PHP 8.1+
- MySQL/MariaDB
- Composer
- Servidor de correo (SMTP)

### Pasos para levantar el servicio

**Opción 1: Configuración automática (recomendada):**
```bash
make setup
```

**Opción 2: Pasos manuales:**
```bash
# Iniciar contenedores
make up

# Instalar dependencias
make composerinstall

# Ejecutar migraciones
make migrate

# Ejecutar seeders (datos iniciales)
make seed

# Crear enlaces simbólicos
make slink

# Procesar cola de trabajos
make queue-work
```

**Comandos adicionales útiles:**
```bash
# Refrescar base de datos completa
make db_fresh

# Ejecutar pruebas
make test

# Detener contenedores
make stop

# Eliminar contenedores
make remove
```
## El servicio estará disponible en:
```
http://localhost/api/soap/wallet
```

## Flujo de Pago Completo

1. **Registro de cliente:** `registerClient()`
2. **Recarga de billetera:** `rechargeWallet()`
3. **Inicio de pago:** `pay()` → genera token y envía por email
4. **Confirmación de pago:** `confirmPayment()` → usa token para completar

## Envío de Emails

El sistema utiliza colas para el envío asíncrono de emails:

### Configuración del Mailable
- **Clase:** `App\Mail\SendTokenPayEmail`
- **Vista:** `resources/views/emails/token.blade.php`
- **Queue:** `Mail::to($email)->queue(new SendTokenPayEmail(...))`

### Procesamiento de la cola
```bash
# Ver trabajos en cola
php artisan queue:failed

# Reiniciar trabajos fallidos
php artisan queue:retry all

# Limpiar cola
php artisan queue:clear
```

## Estructura del Proyecto

```
app/
├── Enums/
│   └── ResponseCode.php          # Códigos de respuesta estandarizados
├── Mail/
│   └── SendTokenPayEmail.php     # Mailable para envío de tokens
├── Services/
│   ├── WalletSoapServer.php      # Servidor SOAP principal
│   └── WalletSoapService.php     # Lógica de negocio
└── Models/
    ├── Client.php
    ├── Wallet.php
    └── WalletTransaction.php

resources/
└── views/
    └── emails/
        └── token.blade.php       # Plantilla de email
```