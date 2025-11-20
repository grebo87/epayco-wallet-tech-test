
# Epayco Wallet - REST service (Lumen)

Descripción
-----------

Servicio REST ligero escrito con Lumen que actúa como un proxy hacia un servicio SOAP interno. Proporciona endpoints para: registrar clientes, consultar saldo, recargar monedero, iniciar pagos y confirmar pagos.

Requisitos
----------

- PHP 7.4+ (o la versión que soporte Lumen en este proyecto)
- Composer
- Extensión SOAP de PHP habilitada
- Docker y docker-compose (opcional)

Variables de entorno importantes
-------------------------------

El servicio usa variables de entorno para configurar el cliente SOAP. Defina al menos las siguientes en su `.env`:

- SOAP_URI: URI del servicio SOAP (por ejemplo: `http://soap.example.com/` o el valor necesario por la configuración)
- SOAP_LOCATION: Location para SoapClient (por ejemplo: `http://soap.example.com/service` o el valor necesario por la configuración)

Si no se configuran, el servicio responderá con un objeto JSON indicando que el endpoint SOAP no está configurado.

Cómo levantar el proyecto
-------------------------

1) Instalar dependencias (local):

```bash
composer install
```

2) Crear o ajustar archivo de entorno (opcional):

```bash
cp .env.example .env   # si existe
# o crear .env manualmente y añadir las variables SOAP_URI y SOAP_LOCATION
```

3a) Levantar con servidor PHP builtin (desarrollo):

```bash
php -S localhost:8000 -t public
```

3b) Levantar con Docker (si está disponible):

```bash
docker compose up --build
```

4) Probar que el servicio está corriendo:

Abrir http://localhost:8000 en el navegador o hacer una petición a un endpoint API (ejemplos abajo).

Endpoints y descripción de métodos
----------------------------------

Todos los endpoints aceptan JSON y retornan JSON. Son peticiones HTTP POST.

1) POST /api/registerClient
	 - Descripción: Registra un cliente en el servicio SOAP.
	 - Parámetros (JSON):
		 - document (string) - requerido
		 - name (string) - requerido
		 - email (string, email) - requerido
		 - phone (string) - requerido
	 - Respuesta: reenvía la respuesta del servicio SOAP (o un objeto con success=false si no hay configuración)

Ejemplo curl:

```bash
curl -s -X POST http://localhost:8000/api/registerClient \
	-H "Content-Type: application/json" \
	-d '{"document":"12345678","name":"Juan Pérez","email":"juan@example.com","phone":"3001234567"}'
```

2) POST /api/checkBalance
	 - Descripción: Consulta el saldo del cliente.
	 - Parámetros (JSON):
		 - document (string) - requerido
		 - phone (string) - requerido

Ejemplo curl:

```bash
curl -s -X POST http://localhost:8000/api/checkBalance \
	-H "Content-Type: application/json" \
	-d '{"document":"12345678","phone":"3001234567"}'
```

3) POST /api/rechargeWallet
	 - Descripción: Recarga el monedero del cliente con un monto numérico.
	 - Parámetros (JSON):
		 - document (string) - requerido
		 - phone (string) - requerido
		 - amount (number) - requerido

Ejemplo curl:

```bash
curl -s -X POST http://localhost:8000/api/rechargeWallet \
	-H "Content-Type: application/json" \
	-d '{"document":"12345678","phone":"3001234567","amount":50000}'
```

4) POST /api/pay
	 - Descripción: Inicia un pago desde el monedero del cliente.
	 - Parámetros (JSON):
		 - document (string) - requerido
		 - phone (string) - requerido
		 - amount (number) - requerido

Ejemplo curl:

```bash
curl -s -X POST http://localhost:8000/api/pay \
	-H "Content-Type: application/json" \
	-d '{"document":"12345678","phone":"3001234567","amount":25000}'
```

5) POST /api/confirmPayment
	 - Descripción: Confirma un pago iniciado (por ejemplo, después de un flujo externo que devuelve token/session).
	 - Parámetros (JSON):
		 - idSession (string) - requerido
		 - token (string) - requerido

Ejemplo curl:

```bash
curl -s -X POST http://localhost:8000/api/confirmPayment \
	-H "Content-Type: application/json" \
	-d '{"idSession":"abc123","token":"token-de-confirmacion"}'
```

Formato de respuestas y manejo de errores
----------------------------------------

El controlador delega a `App\Services\EpaycoSoapClient::call`. Si no hay configuración SOAP se devuelve:

```json
{
	"success": false,
	"message": "SOAP endpoint not configured."
}
```

Se incluye una colección de Postman en `docs/Epayco-Wallet-API.postman_collection.json` para probar los endpoints en herramientas como Insomnia o Postman, se tiene que configurar `{{base_url}}` con la url de la API (por ejemplo, http://localhost:8000).
