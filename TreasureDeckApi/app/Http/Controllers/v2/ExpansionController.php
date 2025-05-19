<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExpansionDTO;
use App\Models\Expansion;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="expansions",
 *     description="Operaciones relacionadas con las expansiones de cartas"
 * )
 */
class ExpansionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v2/expansions",
     *     tags={"expansions"},
     *     summary="Listar todas las expansiones",
     * security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de expansiones"
     *     )
     * )
     */
    public function index() {
        return ExpansionDTO::collection(Expansion::all());
    }

    /**
     * @OA\Post(
     *     path="/api/v2/expansions",
     *     tags={"expansions"},
     *     summary="Crear una nueva expansión",
     * security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Nombre de expansión")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Expansión creada con éxito"
     *     )
     * )
     */
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|unique:expansions,name']);
        return new ExpansionDTO(Expansion::create($request->all()));
    }

    /**
     * @OA\Get(
     *     path="/api/v2/expansions/{id}",
     *     tags={"expansions"},
     *     summary="Obtener una expansión por ID",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la expansión",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la expansión"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Expansión no encontrada"
     *     )
     * )
     */
    public function show($id) {
        $expansion = Expansion::findOrFail($id); 
        return new ExpansionDTO($expansion);
    }

    /**
     * @OA\Put(
     *     path="/api/v2/expansions/{id}",
     *     tags={"expansions"},
     *     summary="Actualizar una expansión existente",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la expansión",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Nuevo nombre de expansión")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Expansión actualizada"
     *     )
     * )
     */
    public function update(Request $request, $id) {
        $expansion = Expansion::findOrFail($id);
        $expansion->update($request->all());
        return new ExpansionDTO($expansion);
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/expansions/{id}",
     *     tags={"expansions"},
     *     summary="Eliminar una expansión",
     * security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la expansión",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Expansión eliminada"
     *     )
     * )
     */
    public function destroy( $id) {
        $expansion = Expansion::findOrFail($id);
        $expansion->delete();
        return response()->noContent();
    }
}
