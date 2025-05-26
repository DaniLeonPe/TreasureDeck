# App Móvil – React Native

- **Lenguaje:** JavaScript / TypeScript  
- **Framework:** React Native  
- **Gestor de paquetes:** npm  
- **Ecosistema:** Android  

## Estructura de Carpetas Principal

```bash
    TreasureDeckApp/
│
├── src/
│ ├── api/ → Configuración de Axios y peticiones a la API
│ ├── constants/ → Estilos globales
│ ├── navigation/ → Stack, tab y drawer navigators
│ ├── screens/ → Pantallas principales (Inicio, Cartas, Colleccion, etc.)
│ └── utils/ → Funciones auxiliares, validaciones, helpers
│
├── assets/ → Imágenes, iconos, fuentes
├── App.js → Punto de entrada de la app
├── .env → Variables de entorno
└── package.json
```


## Comunicación con el Backend

Se usa **Axios** como cliente HTTP (archivo `/src/api/api.js`).  
Las rutas de la API están centralizadas en un archivo para facilitar su gestión y multientorno.  
La variable `API_BASE_URL` se carga desde `api.ts`.

```javascript
export const API_BASE_URL = 'http://192.168.1.40:8000/api';

import axios from "axios";

 const res = await axios.post(`${API_BASE_URL}/login`, { name, password });
