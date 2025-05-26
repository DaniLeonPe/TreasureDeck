# Documentación Técnica del Proyecto

---

## API REST – Backend (Spring Boot)

- **Lenguaje:** PHP 8.1.12
- **Framework:** Laravel 10.48.25 
- **Seguridad:** JWT  
- **Base de datos:** MySQL  
- **Documentación:** Swagger  



### Endpoints

## 1. Auth  
Operaciones de autenticación y registro de usuarios.

| Método | Endpoint       | Descripción            |
|--------|----------------|------------------------|
| POST   | `/api/register` | Registrar usuario      |
| POST   | `/api/login`    | Iniciar sesión         |

---

## 2. Cards  
Operaciones relacionadas con cartas.

| Método | Endpoint            | Descripción                 |
|--------|---------------------|-----------------------------|
| GET    | `/api/v2/cards`     | Obtener lista de cartas con paginación |
| POST   | `/api/v2/cards`     | Crear una nueva carta       |
| GET    | `/api/v2/cards/{id}`| Obtener una carta por ID    |
| PUT    | `/api/v2/cards/{id}`| Actualizar carta por ID     |
| DELETE | `/api/v2/cards/{id}`| Eliminar carta por ID       |

---

## 3. CardsVersions  
Operaciones relacionadas con versiones de cartas.

| Método | Endpoint                  | Descripción                   |
|--------|---------------------------|-------------------------------|
| GET    | `/api/v2/cardsVersion`    | Obtener todas las versiones   |
| POST   | `/api/v2/cardsVersion`    | Crear nueva versión           |
| GET    | `/api/v2/cardsVersion/{id}` | Obtener versión por ID       |
| PUT    | `/api/v2/cardsVersion/{id}` | Actualizar versión           |
| DELETE | `/api/v2/cardsVersion/{id}` | Eliminar versión             |

---

## 4. DeckCards  
Operaciones relacionadas con cartas dentro de mazos.

| Método | Endpoint                 | Descripción                    |
|--------|--------------------------|--------------------------------|
| GET    | `/api/v2/deckCards`      | Listar cartas en mazos         |
| POST   | `/api/v2/deckCards`      | Agregar carta a mazo           |
| GET    | `/api/v2/deckCards/{id}` | Mostrar carta específica       |
| PUT    | `/api/v2/deckCards/{id}` | Actualizar carta en mazo       |
| DELETE | `/api/v2/deckCards/{id}` | Eliminar carta del mazo        |

---

## 5. Decks  
Operaciones relacionadas con mazos.

| Método | Endpoint              | Descripción                 |
|--------|-----------------------|-----------------------------|
| GET    | `/api/v2/decks`       | Obtener todos los mazos     |
| POST   | `/api/v2/decks`       | Crear nuevo mazo            |
| GET    | `/api/v2/decks/{id}`  | Mostrar mazo específico     |
| PUT    | `/api/v2/decks/{id}`  | Actualizar mazo existente   |
| DELETE | `/api/v2/decks/{id}`  | Eliminar mazo               |

---

## 6. DeckStats  
Operaciones relacionadas con estadísticas de mazos.

| Método | Endpoint                  | Descripción                  |
|--------|---------------------------|------------------------------|
| GET    | `/api/v2/deckStats`       | Listar estadísticas de mazos |
| POST   | `/api/v2/deckStats`       | Crear estadísticas para mazo |
| GET    | `/api/v2/deckStats/{id}`  | Mostrar estadísticas por ID  |
| PUT    | `/api/v2/deckStats/{id}`  | Actualizar estadísticas      |
| DELETE | `/api/v2/deckStats/{id}`  | Eliminar estadísticas        |

---

## 7. Expansions  
Operaciones relacionadas con expansiones de cartas.

| Método | Endpoint                  | Descripción                  |
|--------|---------------------------|------------------------------|
| GET    | `/api/v2/expansions`      | Listar expansiones           |
| POST   | `/api/v2/expansions`      | Crear nueva expansión        |
| GET    | `/api/v2/expansions/{id}` | Obtener expansión por ID     |
| PUT    | `/api/v2/expansions/{id}` | Actualizar expansión         |
| DELETE | `/api/v2/expansions/{id}` | Eliminar expansión           |

---

## 8. Role  
Operaciones relacionadas con roles de usuario.

| Método | Endpoint           | Descripción             |
|--------|--------------------|-------------------------|
| GET    | `/api/v2/role`     | Listar roles            |
| POST   | `/api/v2/role`     | Crear rol               |
| GET    | `/api/v2/role/{id}`| Obtener rol por ID      |
| PUT    | `/api/v2/role/{id}`| Actualizar rol          |
| DELETE | `/api/v2/role/{id}`| Eliminar rol            |

---

## 9. UserCards  
Operaciones relacionadas con cartas del usuario autenticado.

| Método | Endpoint                 | Descripción                  |
|--------|--------------------------|------------------------------|
| GET    | `/api/v2/userCards`      | Listar cartas del usuario    |
| POST   | `/api/v2/userCards`      | Crear carta para usuario     |
| GET    | `/api/v2/userCards/{id}` | Obtener carta específica     |
| PUT    | `/api/v2/userCards/{id}` | Actualizar carta del usuario |
| DELETE | `/api/v2/userCards/{id}` | Eliminar carta del usuario   |

---

## 10. Users  
Operaciones relacionadas con usuarios.

| Método | Endpoint             | Descripción              |
|--------|----------------------|--------------------------|
| GET    | `/api/v2/users`      | Listar usuarios          |
| POST   | `/api/v2/users`      | Crear usuario            |
| GET    | `/api/v2/users/{id}` | Obtener usuario por ID   |
| PUT    | `/api/v2/users/{id}` | Actualizar usuario       |
| DELETE | `/api/v2/users/{id}` | Eliminar usuario         |

---


## Seguridad

- Autenticación con token JWT.   
- Verificación de cuentas por correo (Mail Sender).  

---

## Testing

- PHPUnit y coverage para tests de:  
  - Controladores   
  - Models
- Cobertura de código: **88,21%** (generada con Coverage).  

![Test](../img/test.png)
---

## App Móvil – React Native

- **Lenguaje:** JavaScript / TypeScript  
- **Framework:** React Native  

### Librerías clave

- `react-navigation` → Navegación por pantallas.  
- `axios` → Llamadas a la API.  
- `async-storage` → Modo offline.  

### 📲 Pantallas principales

- Registro / Login  
- Cartas
- Collecion
- Decks 
- Crear Decks  
- Informacion de la carta

---



## Despliegue (Futuro)

- Backend: Desplegado en DigitalOcean.  
- App: Compilada como APK para Android o IPA para iOS.  
- Admin: Dolibarr instalado en servidor Apache o Nginx.  

---


# Sistema Administrativo – Dolibarr

**Propósito:** Gestión de la empresa que comercializa la app.

## Configuraciones básicas:

- Gestión de clientes y proveedores.
- Productos/servicios.
- Presupuestos, facturación y pedidos.
- Logotipo y diseño personalizado.


---

