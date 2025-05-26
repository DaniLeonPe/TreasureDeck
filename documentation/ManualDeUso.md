# Manual de Uso y Configuración – CanaryTrails

Este documento detalla los pasos necesarios para instalar, configurar y ejecutar el proyecto CanaryTrails en entorno local. El proyecto está compuesto por tres partes principales:

- Backend (PHP Laravel)  
- Aplicación móvil (React Native)  
- Sistema de gestión empresarial (Dolibarr)  

---

## 1. Requisitos Previos

**Software necesario:**

- PHP 8.1+
- Node.js 18+
- npm
- MySQL 8+
- Android Studio
- Git

---

## 2. Backend – API REST (Spring Boot)

### Configuración local

1. Clona el repositorio:
   ```bash
   git clone https://github.com/DaniLeonPe/TreasureDeck
   cd TreasureDeck/TreasureDeckApi
    ```

2. Crea la base de datos en Mysql a partir de las migraciones de laravel 
    ```bash
        php artisan migrate:fresh
    ```
3. Configura .env 
    ```bash
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=treasuredeck
        DB_USERNAME=nombre-usuario
        DB_PASSWORD=contrasenia
    ```

4. Ejecuta el servidor
    ```bash
         php artisan serve --host=0.0.0.0 --port=8000
    ```

5. Accede a la API
    - Swagger UI `http://localhost:8000/api/documentation`

## 3. APP Móvil - React Native

1. Ve al directorio del cliente
    ```bash
        cd TreasureDeck/TreasureDeckApp
    ```
2. Instala dependencias
    ```bash
        npm install
    ```
3. Ejecutamos el proyecto mediante Metro:
     ```bash
    npm run start
    ```
    y elegimos la opcion a para lanzarlo en android
    ```bash
    
              Welcome to Metro v0.81.0
            Fast - Scalable - Integrated
        info Dev server ready

        i - run on iOS
        a - run on Android
        r - reload app
        d - open Dev Menu
        j - open DevTools

    ```