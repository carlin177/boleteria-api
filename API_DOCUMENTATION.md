# 📚 DOCUMENTACIÓN API REST - BOLETERÍA DE COLECTIVOS

## 🌐 BASE URL
```
http://localhost:8000/api/v1
```

---

## 📋 ENDPOINTS - VIAJES

### 1. Listar todos los viajes (PÚBLICO)
```http
GET /api/v1/viajes
```

**Parámetros de consulta (opcionales):**
- `empresa_id` - Filtrar por empresa
- `ciudad_origen_id` - Filtrar por ciudad de origen
- `ciudad_destino_id` - Filtrar por ciudad de destino
- `hora_salida` - Filtrar viajes desde una hora
- `page` - Número de página (paginación de 15 items)

**Ejemplo:**
```
GET /api/v1/viajes?empresa_id=1&ciudad_origen_id=2
```

**Respuesta (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "empresa_id": 1,
      "ciudad_origen_id": 1,
      "ciudad_destino_id": 2,
      "creado_por_id": 1,
      "hora_salida": "14:30:00",
      "hora_llegada": "18:45:00",
      "tipo_servicio": "Cama",
      "precio": "150.50",
      "asientos_totales": 45,
      "estado": "activo",
      "observaciones": null,
      "created_at": "2026-05-15T10:00:00Z",
      "updated_at": "2026-05-15T10:00:00Z",
      "empresa": {
        "id": 1,
        "nombre": "Río Uruguay",
        ...
      },
      "ciudadOrigen": {
        "id": 1,
        "nombre": "Goya",
        ...
      },
      "ciudadDestino": {
        "id": 2,
        "nombre": "Corrientes",
        ...
      }
    }
  ],
  "pagination": {
    "total": 50,
    "per_page": 15,
    "current_page": 1,
    "last_page": 4,
    "from": 1,
    "to": 15
  },
  "message": "Viajes obtenidos exitosamente"
}
```

---

### 2. Obtener un viaje específico (PÚBLICO)
```http
GET /api/v1/viajes/{id}
```

**Respuesta (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "empresa_id": 1,
    "ciudad_origen_id": 1,
    "ciudad_destino_id": 2,
    "hora_salida": "14:30:00",
    "hora_llegada": "18:45:00",
    "tipo_servicio": "Cama",
    "precio": "150.50",
    "asientos_totales": 45,
    "estado": "activo",
    "observaciones": "Parada en Paso de la Cruz",
    "empresa": { ... },
    "ciudadOrigen": { ... },
    "ciudadDestino": { ... },
    "creadoPor": { ... }
  },
  "message": "Viaje obtenido exitosamente"
}
```

---

### 3. Crear un viaje (ADMIN ONLY)
```http
POST /api/v1/viajes
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "empresa_id": 1,
  "ciudad_origen_id": 1,
  "ciudad_destino_id": 2,
  "hora_salida": "14:30:00",
  "hora_llegada": "18:45:00",
  "tipo_servicio": "Cama",
  "precio": 150.50,
  "asientos_totales": 45,
  "estado": "activo",
  "observaciones": "Parada en Paso de la Cruz"
}
```

**Validaciones:**
- `empresa_id` - Requerido, debe existir
- `ciudad_origen_id` - Requerido, debe existir, diferente al destino
- `ciudad_destino_id` - Requerido, debe existir, diferente al origen
- `hora_salida` - Requerido, formato HH:MM:SS
- `hora_llegada` - Requerido, debe ser posterior a hora_salida
- `tipo_servicio` - Requerido, máx 50 caracteres
- `precio` - Requerido, número, mín 0.01
- `asientos_totales` - Opcional, número entero
- `observaciones` - Opcional, máx 1000 caracteres

**Respuesta (201 Created):**
```json
{
  "success": true,
  "data": { ... },
  "message": "Viaje creado exitosamente"
}
```

---

### 4. Actualizar un viaje (ADMIN ONLY)
```http
PUT /api/v1/viajes/{id}
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:** (igual que crear, pero todos los campos son opcionales)

**Respuesta (200 OK):**
```json
{
  "success": true,
  "data": { ... },
  "message": "Viaje actualizado exitosamente"
}
```

---

### 5. Eliminar un viaje (ADMIN ONLY)
```http
DELETE /api/v1/viajes/{id}
Authorization: Bearer {token}
```

**Respuesta (204 No Content):**
```json
{
  "success": true,
  "message": "Viaje eliminado exitosamente"
}
```

---

## 🏢 ENDPOINTS - EMPRESAS

### 1. Listar todas las empresas (PÚBLICO)
```http
GET /api/v1/empresas
```

**Respuesta (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "Río Uruguay",
      "telefono": "+54 123 456 7890",
      "email": "contacto@riouruguay.com",
      "sitio_web": "https://www.riouruguay.com",
      "estado": "activo",
      "created_at": "2026-05-15T10:00:00Z",
      "updated_at": "2026-05-15T10:00:00Z"
    }
  ],
  "message": "Empresas obtenidas exitosamente"
}
```

---

### 2. Obtener una empresa con sus viajes (PÚBLICO)
```http
GET /api/v1/empresas/{id}
```

**Respuesta (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "nombre": "Río Uruguay",
    "telefono": "+54 123 456 7890",
    "email": "contacto@riouruguay.com",
    "sitio_web": "https://www.riouruguay.com",
    "estado": "activo",
    "viajes": [ ... ]
  },
  "message": "Empresa obtenida exitosamente"
}
```

---

### 3. Crear empresa (ADMIN ONLY)
```http
POST /api/v1/empresas
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "nombre": "Flecha Bus",
  "telefono": "+54 987 654 3210",
  "email": "info@flechabus.com",
  "sitio_web": "https://www.flechabus.com",
  "estado": "activo"
}
```

---

### 4. Actualizar empresa (ADMIN ONLY)
```http
PUT /api/v1/empresas/{id}
Authorization: Bearer {token}
Content-Type: application/json
```

---

### 5. Eliminar empresa (ADMIN ONLY)
```http
DELETE /api/v1/empresas/{id}
Authorization: Bearer {token}
```

---

## 🌍 ENDPOINTS - CIUDADES

### 1. Listar todas las ciudades (PÚBLICO)
```http
GET /api/v1/ciudades
```

**Respuesta (200 OK):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "nombre": "Corrientes",
      "provincia": "Corrientes",
      "pais": "Argentina",
      "estado": "activo",
      "created_at": "2026-05-15T10:00:00Z",
      "updated_at": "2026-05-15T10:00:00Z"
    }
  ],
  "message": "Ciudades obtenidas exitosamente"
}
```

---

### 2. Obtener una ciudad (PÚBLICO)
```http
GET /api/v1/ciudades/{id}
```

**Respuesta (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "nombre": "Corrientes",
    "provincia": "Corrientes",
    "pais": "Argentina",
    "estado": "activo",
    "viajesOrigen": [ ... ],
    "viajesDestino": [ ... ]
  },
  "message": "Ciudad obtenida exitosamente"
}
```

---

### 3. Crear ciudad (ADMIN ONLY)
```http
POST /api/v1/ciudades
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
  "nombre": "Goya",
  "provincia": "Corrientes",
  "pais": "Argentina",
  "estado": "activo"
}
```

---

### 4. Actualizar ciudad (ADMIN ONLY)
```http
PUT /api/v1/ciudades/{id}
Authorization: Bearer {token}
Content-Type: application/json
```

---

### 5. Eliminar ciudad (ADMIN ONLY)
```http
DELETE /api/v1/ciudades/{id}
Authorization: Bearer {token}
```

**Nota:** No se puede eliminar una ciudad que tenga viajes asociados.

---

## 🔐 AUTENTICACIÓN

### Tokens Bearer
Todas las rutas protegidas requieren un token Bearer en el header:

```http
Authorization: Bearer {token_aquí}
```

**Ejemplo con cURL:**
```bash
curl -X POST http://localhost:8000/api/v1/viajes \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc..." \
  -H "Content-Type: application/json" \
  -d '{
    "empresa_id": 1,
    "ciudad_origen_id": 1,
    ...
  }'
```

---

## ✅ HEALTH CHECK

### Verificar estado de la API
```http
GET /api/v1/health
```

**Respuesta (200 OK):**
```json
{
  "success": true,
  "message": "API Health Check OK",
  "timestamp": "2026-05-15T10:00:00Z"
}
```

---

## 🚨 CÓDIGOS DE RESPUESTA

| Código | Descripción |
|--------|-------------|
| **200 OK** | Solicitud exitosa |
| **201 Created** | Recurso creado exitosamente |
| **204 No Content** | Solicitud exitosa sin contenido |
| **400 Bad Request** | Error en validación de datos |
| **401 Unauthorized** | Sin autenticación |
| **403 Forbidden** | No autorizado (no es admin) |
| **404 Not Found** | Recurso no encontrado |
| **409 Conflict** | Conflicto (ej: ciudad duplicada) |
| **500 Internal Server Error** | Error del servidor |

---

## 📌 NOTAS IMPORTANTES

1. **Paginación:** Los listados de viajes están paginados (15 items por página)
2. **Solo Admin:** Las operaciones de crear, actualizar y eliminar requieren rol 'admin'
3. **Validación de Fechas:** Las horas deben estar en formato HH:MM:SS (24 horas)
4. **Relaciones:** Los datos incluyen automáticamente relaciones (empresa, ciudades, usuario)
5. **Filtros en Viajes:** Se pueden combinar múltiples filtros en el listado

---

## 🧪 EJEMPLOS CON POSTMAN

### Listar viajes con filtros
```http
GET /api/v1/viajes?empresa_id=1&ciudad_origen_id=1&page=1
```

### Crear viaje nuevo
```http
POST /api/v1/viajes
{
  "empresa_id": 1,
  "ciudad_origen_id": 1,
  "ciudad_destino_id": 2,
  "hora_salida": "14:30:00",
  "hora_llegada": "18:45:00",
  "tipo_servicio": "Cama",
  "precio": 150.50
}
```

### Actualizar estado de viaje
```http
PUT /api/v1/viajes/1
{
  "estado": "inactivo"
}
```

---

## 📞 SOPORTE

Para reportar errores o consultas sobre la API, contacta al equipo de desarrollo.
