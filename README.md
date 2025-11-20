# Docker Compose - Epayco Services

## Descargar el proyecto
```bash
git clone https://github.com/grebo87/epayco-wallet-tech-test.git
```

```bash
cd epayco-wallet-tech-test
```

Este proyecto utiliza Docker Compose para orquestar tres servicios principales:

## Configuración de Puertos

- **SOAP Service (Laravel)**: `http://localhost:8001`
- **REST Service (Lumen)**: `http://localhost:8002`
- **MySQL**: `localhost:3306`

## Comunicación entre Servicios

Los servicios pueden comunicarse usando los nombres de contenedor:

- SOAP Service accede a REST: `http://rest-nginx`
- REST Service accede a SOAP: `http://soap-app`
- SOAP Service accede a MySQL: `mysql`


## Variables de Entorno

Entrar al directorio de cada servicio y si no existe el archivo `.env`, ejecutar:
```bash
cd soap-service
cp .env.example .env

cd ../rest-service
cp .env.example .env
```
Edita el archivo `.env` para personalizar:
- Credenciales de base de datos
- Configuración de Xdebug
- Usuarios de Docker


## Comandos

### Antes de levantar los servicios

Asegúrate de haber ejecutado los pasos de configuración inicial descritos anteriormente y estar en la raíz del proyecto (donde está este archivo README.md).

```bash
cd ..
```

### Levantar todos los servicios:
```bash
docker compose up -d
```

### Ver logs:
```bash
docker compose logs -f
```

### Logs de un servicio específico:
```bash
docker compose logs -f soap-app
docker compose logs -f rest-app
```

### Detener servicios:
```bash
docker compose down
```

### Reconstruir imágenes:
```bash
docker compose build --no-cache
```

## Base de Datos

La instancia MySQL crea automáticamente la base de datos(epayco_wallet) que es utilizada por:
- `el servicio SOAP` (Laravel)

## Configuración Inicial

1. **Preparar el entorno**:
   ```bash
   # Instalar dependencias
   docker compose exec soap-app composer install
   docker compose exec rest-app composer install

   # Generar clave de aplicación
   docker compose exec soap-app php artisan key:generate
   docker compose exec rest-app php artisan key:generate
   ```

2. **Configurar base de datos**:
   Asegúrate de que el archivo `.env` del servicio SOAP tenga la configuración correcta:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=epayco_wallet
   DB_USERNAME=epayco
   DB_PASSWORD=epayco
   ```

3. **Ejecutar migraciones**:
   ```bash
   docker compose exec soap-app php artisan migrate
   ```
4. ** Ejecutar el worker de Laravel, para enviar el email con el token de confirmación**:
   ```bash
   docker compose exec soap-app php artisan queue:work
   ```
## Notas Importantes

1. Entra a la carpeta de cada servicio (soap-service y rest-service) para ver los comandos de configuración específicos
2. Asegúrate de que los puertos 8001, 8002 y 3306 estén disponibles
3. Los volúmenes persisten los datos de MySQL
4. Para ejecutar comandos en los contenedores:
   ```bash
   # Acceder a la consola del contenedor
   docker compose exec soap-app bash
   docker compose exec rest-app bash
   ```
5. **Reconstruir el proyecto** (si es necesario):
   ```bash
   # Detener los contenedores
   docker compose down

   # Reconstruir imágenes
   docker compose build --no-cache

   # Iniciar los contenedores
   docker compose up -d
   ```

## Epayco Wallet Frontend (demo)

en la carpeta frontend-vue esta una pequeña SPA para probar el servicio rest

Instalación y ejecución:

```bash
cd frontend-vue
npm install
npm run dev
```