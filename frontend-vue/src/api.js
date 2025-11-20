import axios from 'axios'

// En desarrollo Vite usa el proxy configurado para /api, por eso baseURL está vacío.
const isDev = import.meta.env.DEV
const baseURL = isDev ? '' : (import.meta.env.VITE_API_BASE || 'http://localhost:8002')

const api = axios.create({ baseURL })

export default api
