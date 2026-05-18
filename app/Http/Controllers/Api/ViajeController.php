<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Viaje;
use App\Models\Empresa;
use App\Models\Ciudad;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ViajeController extends Controller
{
    /**
     * Listar todos los viajes activos (público).
     */
    public function index(Request $request)
    {
        $query = Viaje::where('estado', 'activo')
            ->with(['empresa', 'ciudadOrigen', 'ciudadDestino']);

        // Filtros opcionales
        if ($request->has('empresa_id')) {
            $query->where('empresa_id', $request->empresa_id);
        }

        if ($request->has('ciudad_origen_id')) {
            $query->where('ciudad_origen_id', $request->ciudad_origen_id);
        }

        if ($request->has('ciudad_destino_id')) {
            $query->where('ciudad_destino_id', $request->ciudad_destino_id);
        }

        if ($request->has('hora_salida')) {
            $query->where('hora_salida', '>=', $request->hora_salida);
        }

        $viajes = $query->orderBy('hora_salida')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $viajes->items(),
            'pagination' => [
                'total' => $viajes->total(),
                'per_page' => $viajes->perPage(),
                'current_page' => $viajes->currentPage(),
                'last_page' => $viajes->lastPage(),
                'from' => $viajes->firstItem(),
                'to' => $viajes->lastItem(),
            ],
            'message' => 'Viajes obtenidos exitosamente'
        ], Response::HTTP_OK);
    }

    /**
     * Crear un nuevo viaje (solo admin).
     */
    public function store(Request $request)
    {
        // Validar que sea admin
        if (!auth()->check() || auth()->user()->rol !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado. Solo administradores pueden crear viajes.'
            ], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'ciudad_origen_id' => 'required|exists:ciudades,id',
            'ciudad_destino_id' => 'required|exists:ciudades,id|different:ciudad_origen_id',
            'hora_salida' => 'required|date_format:H:i:s',
            'hora_llegada' => 'required|date_format:H:i:s|after:hora_salida',
            'tipo_servicio' => 'required|string|max:50',
            'precio' => 'required|numeric|min:0.01|max:9999999.99',
            'asientos_totales' => 'nullable|integer|min:1',
            'estado' => 'nullable|in:activo,inactivo',
            'observaciones' => 'nullable|string|max:1000'
        ]);

        // Agregar el usuario que crea el viaje
        $validated['creado_por_id'] = auth()->id();

        $viaje = Viaje::create($validated);

        // Cargar relaciones
        $viaje->load(['empresa', 'ciudadOrigen', 'ciudadDestino', 'creadoPor']);

        return response()->json([
            'success' => true,
            'data' => $viaje,
            'message' => 'Viaje creado exitosamente'
        ], Response::HTTP_CREATED);
    }

    /**
     * Obtener un viaje específico (público).
     */
    public function show($id)
    {
        $viaje = Viaje::with(['empresa', 'ciudadOrigen', 'ciudadDestino', 'creadoPor'])
            ->find($id);

        if (!$viaje) {
            return response()->json([
                'success' => false,
                'message' => 'Viaje no encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => $viaje,
            'message' => 'Viaje obtenido exitosamente'
        ], Response::HTTP_OK);
    }

    /**
     * Actualizar un viaje (solo admin).
     */
    public function update(Request $request, $id)
    {
        // Validar que sea admin
        if (!auth()->check() || auth()->user()->rol !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado. Solo administradores pueden actualizar viajes.'
            ], Response::HTTP_FORBIDDEN);
        }

        $viaje = Viaje::find($id);

        if (!$viaje) {
            return response()->json([
                'success' => false,
                'message' => 'Viaje no encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'empresa_id' => 'sometimes|required|exists:empresas,id',
            'ciudad_origen_id' => 'sometimes|required|exists:ciudades,id',
            'ciudad_destino_id' => 'sometimes|required|exists:ciudades,id|different:ciudad_origen_id',
            'hora_salida' => 'sometimes|required|date_format:H:i:s',
            'hora_llegada' => 'sometimes|required|date_format:H:i:s|after:hora_salida',
            'tipo_servicio' => 'sometimes|required|string|max:50',
            'precio' => 'sometimes|required|numeric|min:0.01|max:9999999.99',
            'asientos_totales' => 'nullable|integer|min:1',
            'estado' => 'nullable|in:activo,inactivo',
            'observaciones' => 'nullable|string|max:1000'
        ]);

        $viaje->update($validated);

        // Recargar relaciones
        $viaje->load(['empresa', 'ciudadOrigen', 'ciudadDestino', 'creadoPor']);

        return response()->json([
            'success' => true,
            'data' => $viaje,
            'message' => 'Viaje actualizado exitosamente'
        ], Response::HTTP_OK);
    }

    /**
     * Eliminar un viaje (solo admin).
     */
    public function destroy($id)
    {
        // Validar que sea admin
        if (!auth()->check() || auth()->user()->rol !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado. Solo administradores pueden eliminar viajes.'
            ], Response::HTTP_FORBIDDEN);
        }

        $viaje = Viaje::find($id);

        if (!$viaje) {
            return response()->json([
                'success' => false,
                'message' => 'Viaje no encontrado'
            ], Response::HTTP_NOT_FOUND);
        }

        $viaje->delete();

        return response()->json([
            'success' => true,
            'message' => 'Viaje eliminado exitosamente'
        ], Response::HTTP_NO_CONTENT);
    }
}
