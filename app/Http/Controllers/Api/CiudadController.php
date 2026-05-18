<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ciudad;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CiudadController extends Controller
{
    /**
     * Listar todas las ciudades activas.
     */
    public function index()
    {
        $ciudades = Ciudad::orderBy('nombre')->get();

        return response()->json([
            'success' => true,
            'data' => $ciudades,
            'message' => 'Ciudades obtenidas exitosamente'
        ], Response::HTTP_OK);
    }

    /**
     * Crear una nueva ciudad (solo admin).
     */
    public function store(Request $request)
    {
        // Validar que sea admin
        if (auth()->check() && auth()->user()->rol !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado. Solo administradores pueden crear ciudades.'
            ], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'pais' => 'required|string|max:100',
            'estado' => 'nullable|in:activo,inactivo'
        ]);

        // Validar que la combinación nombre-provincia-país sea única
        $existe = Ciudad::where('nombre', $validated['nombre'])
            ->where('provincia', $validated['provincia'] ?? null)
            ->where('pais', $validated['pais'])
            ->exists();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Esta ciudad ya existe en el sistema.'
            ], Response::HTTP_CONFLICT);
        }

        $ciudad = Ciudad::create($validated);

        return response()->json([
            'success' => true,
            'data' => $ciudad,
            'message' => 'Ciudad creada exitosamente'
        ], Response::HTTP_CREATED);
    }

    /**
     * Obtener una ciudad específica con sus viajes.
     */
    public function show($id)
    {
        $ciudad = Ciudad::with(['viajesOrigen', 'viajesDestino'])
            ->find($id);

        if (!$ciudad) {
            return response()->json([
                'success' => false,
                'message' => 'Ciudad no encontrada'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => $ciudad,
            'message' => 'Ciudad obtenida exitosamente'
        ], Response::HTTP_OK);
    }

    /**
     * Actualizar una ciudad (solo admin).
     */
    public function update(Request $request, $id)
    {
        // Validar que sea admin
        if (auth()->check() && auth()->user()->rol !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado. Solo administradores pueden actualizar ciudades.'
            ], Response::HTTP_FORBIDDEN);
        }

        $ciudad = Ciudad::find($id);

        if (!$ciudad) {
            return response()->json([
                'success' => false,
                'message' => 'Ciudad no encontrada'
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'provincia' => 'nullable|string|max:100',
            'pais' => 'sometimes|required|string|max:100',
            'estado' => 'nullable|in:activo,inactivo'
        ]);

        $ciudad->update($validated);

        return response()->json([
            'success' => true,
            'data' => $ciudad,
            'message' => 'Ciudad actualizada exitosamente'
        ], Response::HTTP_OK);
    }

    /**
     * Eliminar una ciudad (solo admin).
     */
    public function destroy($id)
    {
        // Validar que sea admin
        if (auth()->check() && auth()->user()->rol !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado. Solo administradores pueden eliminar ciudades.'
            ], Response::HTTP_FORBIDDEN);
        }

        $ciudad = Ciudad::find($id);

        if (!$ciudad) {
            return response()->json([
                'success' => false,
                'message' => 'Ciudad no encontrada'
            ], Response::HTTP_NOT_FOUND);
        }

        // Verificar que no tenga viajes asociados
        if ($ciudad->viajesOrigen()->exists() || $ciudad->viajesDestino()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la ciudad porque tiene viajes asociados.'
            ], Response::HTTP_CONFLICT);
        }

        $ciudad->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ciudad eliminada exitosamente'
        ], Response::HTTP_NO_CONTENT);
    }
}
