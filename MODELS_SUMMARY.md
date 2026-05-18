# 📋 RESUMEN - MODELOS ELOQUENT CREADOS

## ✅ MODELOS COMPLETADOS

### 1️⃣ Model: `Empresa`
- **Ubicación**: `app/Models/Empresa.php`
- **Tabla**: `empresas`
- **Campos fillable**: nombre, telefono, email, sitio_web, estado
- **Relación hasMany**: `viajes()`
- **Descripción**: Representa una empresa de transporte

```php
$empresa = Empresa::find(1);
$viajes = $empresa->viajes; // Obtiene todos los viajes de la empresa
```

---

### 2️⃣ Model: `Ciudad`
- **Ubicación**: `app/Models/Ciudad.php`
- **Tabla**: `ciudades`
- **Campos fillable**: nombre, provincia, pais, estado
- **Relaciones hasMany**:
  - `viajesOrigen()` - Viajes que salen de esta ciudad
  - `viajesDestino()` - Viajes que llegan a esta ciudad
- **Descripción**: Representa una ciudad/ubicación geográfica

```php
$ciudad = Ciudad::find(1);
$salidas = $ciudad->viajesOrigen; // Viajes que salen
$llegadas = $ciudad->viajesDestino; // Viajes que llegan
```

---

### 3️⃣ Model: `Viaje`
- **Ubicación**: `app/Models/Viaje.php`
- **Tabla**: `viajes`
- **Campos fillable**: empresa_id, ciudad_origen_id, ciudad_destino_id, creado_por_id, hora_salida, hora_llegada, tipo_servicio, precio, asientos_totales, estado, observaciones
- **Relaciones belongsTo**:
  - `empresa()` - La empresa que opera el viaje
  - `ciudadOrigen()` - La ciudad de origen
  - `ciudadDestino()` - La ciudad de destino
  - `creadoPor()` - El usuario (admin) que creó el viaje
- **Descripción**: Entidad principal - representa un viaje específico

```php
$viaje = Viaje::with('empresa', 'ciudadOrigen', 'ciudadDestino')->find(1);
echo $viaje->empresa->nombre; // Nombre de la empresa
echo $viaje->ciudadOrigen->nombre; // Ciudad de origen
echo $viaje->ciudadDestino->nombre; // Ciudad de destino
```

---

### 4️⃣ Model: `User` (Actualizado)
- **Ubicación**: `app/Models/User.php`
- **Tabla**: `users`
- **Campos fillable**: name, email, password, **rol** ← NUEVO
- **Casts**: email_verified_at (datetime), password (hashed), **rol** (string) ← NUEVO
- **Relación hasMany**: `viajesCreadosPor()` - Viajes creados por este usuario
- **Descripción**: Usuario admin o visitante del sistema

```php
$user = User::find(1);
$viajesCreadosPor = $user->viajesCreadosPor; // Obtiene todos los viajes que creó
```

---

## 🔗 DIAGRAMA DE RELACIONES COMPLETO

```
┌──────────────┐
│   Empresa    │
├──────────────┤
│ id           │
│ nombre       │
│ ...          │
└──────┬───────┘
       │ hasMany
       │ viajes()
       ▼
  ┌────────────────────────────────────┐
  │          Viaje (Principal)         │
  ├────────────────────────────────────┤
  │ id                                 │
  │ empresa_id (FK) ──────→ Empresa   │
  │ ciudad_origen_id ──────→ Ciudad   │
  │ ciudad_destino_id ─────→ Ciudad   │
  │ creado_por_id ─────────→ User     │
  │ hora_salida                        │
  │ hora_llegada                       │
  │ tipo_servicio                      │
  │ precio                             │
  │ estado                             │
  └────────────────────────────────────┘
       ▲         ▲              ▲
       │         │              │
       │         │              │
  belongsTo  belongsTo    belongsTo
  empresa()  ciudadOrigen() ciudadDestino()
       │         │              │
       │         │              │
       │         │              │
  ┌────┘   ┌─────┘      ┌──────┘
  │        │            │
  │    ┌─────────────────┐
  │    │    Ciudad       │
  │    ├─────────────────┤
  │    │ id              │
  │    │ nombre          │
  │    │ provincia       │
  │    │ pais            │
  │    └─────────────────┘
  │
  │   (hasMany)
  │   viajes()
  │
  ┌──────────────┐
  │    User      │
  ├──────────────┤
  │ id           │
  │ name         │
  │ email        │
  │ rol          │ ← admin/visitante
  │ password     │
  └──────────────┘
  
  viajesCreadosPor()
  (hasMany)
```

---

## 💡 USO PRÁCTICO DE LOS MODELOS

### Crear un viaje
```php
$viaje = Viaje::create([
    'empresa_id' => 1,
    'ciudad_origen_id' => 1,
    'ciudad_destino_id' => 2,
    'creado_por_id' => auth()->id(),
    'hora_salida' => '14:30',
    'hora_llegada' => '18:45',
    'tipo_servicio' => 'Cama',
    'precio' => 150.50,
    'estado' => 'activo',
]);
```

### Obtener un viaje con sus relaciones
```php
$viaje = Viaje::with('empresa', 'ciudadOrigen', 'ciudadDestino', 'creadoPor')
    ->find(1);

// Acceder a los datos relacionados
echo $viaje->empresa->nombre;
echo $viaje->ciudadOrigen->nombre;
echo $viaje->ciudadDestino->nombre;
echo $viaje->creadoPor->name;
```

### Obtener todos los viajes de una empresa
```php
$empresa = Empresa::find(1);
$viajes = $empresa->viajes;
```

### Obtener viajes que salen de una ciudad
```php
$ciudad = Ciudad::find(1);
$viajesSalida = $ciudad->viajesOrigen;
```

---

## ✅ VERIFICACIÓN

Todos los modelos han sido verificados y están funcionando correctamente:
- ✅ Empresa → tabla: `empresas`
- ✅ Ciudad → tabla: `ciudades`
- ✅ Viaje → tabla: `viajes`
- ✅ User → tabla: `users`

Las relaciones están correctamente configuradas con:
- ✅ HasMany relaciones en Empresa, Ciudad, User
- ✅ BelongsTo relaciones en Viaje
- ✅ Casts para tipos de datos
- ✅ Fillable para asignación masiva

---

## 🎯 PRÓXIMO PASO

Ahora podemos crear:
1. **Controllers REST** (ViajeController, EmpresaController, CiudadController)
2. **Rutas API** para consultar y gestionar viajes
3. **Form Requests** para validaciones
4. **Seeders** para datos de prueba

¿Continuamos?
