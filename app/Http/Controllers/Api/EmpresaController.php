<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmpresaController extends Controller
{
    /**
     * Listar todas las empresas activas.
     */
    public function index()
    {
        $empresas = Empresa::orderBy('id')->get();

        return response()->json([
            'success' => true,
            'data' => $empresas,
            'message' => 'Empresas obtenidas exitosamente'
        ], Response::HTTP_OK);
    }

    /**
     * Crear una nueva empresa (solo admin).
     */
    public function store(Request $request)
    {
        // Validar que sea admin
        if (auth()->check() && auth()->user()->rol !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado. Solo administradores pueden crear empresas.'
            ], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validate([
            'id'       => 'nullable|integer|min:1|unique:empresas,id',
            'nombre'   => 'required|string|max:100|unique:empresas,nombre',
            'telefono' => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:100',
            'sitio_web'=> 'nullable|url|max:255',
            'estado'   => 'nullable|in:activo,inactivo'
        ]);

        $empresa = Empresa::create($validated);

        return response()->json([
            'success' => true,
            'data' => $empresa,
            'message' => 'Empresa creada exitosamente'
        ], Response::HTTP_CREATED);
    }

    /**
     * Obtener una empresa específica con sus viajes.
     */
    public function show($id)
    {
        $empresa = Empresa::with('viajes')
            ->find($id);

        if (!$empresa) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa no encontrada'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => $empresa,
            'message' => 'Empresa obtenida exitosamente'
        ], Response::HTTP_OK);
    }

    /**
     * Actualizar una empresa (solo admin).
     */
    public function update(Request $request, $id)
    {
        // Validar que sea admin
        if (auth()->check() && auth()->user()->rol !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado. Solo administradores pueden actualizar empresas.'
            ], Response::HTTP_FORBIDDEN);
        }

        $empresa = Empresa::find($id);

        if (!$empresa) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa no encontrada'
            ], Response::HTTP_NOT_FOUND);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:100|unique:empresas,nombre,' . $id,
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'sitio_web' => 'nullable|url|max:255',
            'estado' => 'nullable|in:activo,inactivo'
        ]);

        $empresa->update($validated);

        return response()->json([
            'success' => true,
            'data' => $empresa,
            'message' => 'Empresa actualizada exitosamente'
        ], Response::HTTP_OK);
    }

    /**
     * Eliminar una empresa (solo admin).
     */
    public function destroy($id)
    {
        // Validar que sea admin
        if (auth()->check() && auth()->user()->rol !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado. Solo administradores pueden eliminar empresas.'
            ], Response::HTTP_FORBIDDEN);
        }

        $empresa = Empresa::find($id);

        if (!$empresa) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa no encontrada'
            ], Response::HTTP_NOT_FOUND);
        }

        $empresa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Empresa eliminada exitosamente'
        ], Response::HTTP_NO_CONTENT);
    }
}
