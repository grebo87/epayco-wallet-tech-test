# Epayco Wallet Frontend (demo)

Pequeña SPA en Vue 3 para consumir la API REST `rest-service`.

Requisitos:
- Node 18+ y npm o yarn

Instalación y ejecución:

```bash
cd frontend-vue
npm install
npm run dev
```

La app asume por defecto que la API está en `http://localhost:8002`. Cambia `VITE_API_BASE` en el archivo `.env` si tu API corre en otro puerto.

Componentes:
- Registrar cliente
- Consultar balance
- Recargar wallet
- Pagar
- Confirmar pago
