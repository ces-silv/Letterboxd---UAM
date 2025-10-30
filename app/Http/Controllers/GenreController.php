<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Géneros",
 *     description="Endpoints para gestión de géneros de películas"
 * )
 */
class GenreController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/genres",
     *     summary="Listar todos los géneros",
     *     tags={"Géneros"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de géneros obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="genre_id", type="integer"),
     *                 @OA\Property(property="genre_name", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $genres = Genre::all();
        return response()->json($genres);
    }

    /**
     * @OA\Post(
     *     path="/api/genres",
     *     summary="Crear nuevo género (Solo admin)",
     *     tags={"Géneros"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"genre_name"},
     *             @OA\Property(property="genre_name", type="string", maxLength=50, example="Acción")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Género creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="genre_id", type="integer"),
     *             @OA\Property(property="genre_name", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Acceso denegado - Se requieren permisos de administrador"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(StoreGenreRequest $request): JsonResponse
    {
        $genre = Genre::create([
            'genre_name' => $request->genre_name,
        ]);

        return response()->json($genre, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/genres/{id}",
     *     summary="Mostrar un género específico",
     *     tags={"Géneros"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del género",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Género encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="genre_id", type="integer"),
     *             @OA\Property(property="genre_name", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Género no encontrado"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $genre = Genre::find($id);
        
        if (!$genre) {
            return response()->json(['message' => 'Género no encontrado'], 404);
        }

        return response()->json($genre);
    }

    /**
     * @OA\Put(
     *     path="/api/genres/{id}",
     *     summary="Actualizar un género",
     *     tags={"Géneros"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del género",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"genre_name"},
     *             @OA\Property(property="genre_name", type="string", maxLength=50, example="Acción")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Género actualizado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Género no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function update(UpdateGenreRequest $request, string $id): JsonResponse
    {
        $genre = Genre::find($id);
        
        if (!$genre) {
            return response()->json(['message' => 'Género no encontrado'], 404);
        }

        $genre->update([
            'genre_name' => $request->genre_name,
        ]);

        return response()->json($genre);
    }

    /**
     * @OA\Delete(
     *     path="/api/genres/{id}",
     *     summary="Eliminar un género (Solo admin)",
     *     tags={"Géneros"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del género",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Género eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Acceso denegado - Se requieren permisos de administrador"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Género no encontrado"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $genre = Genre::find($id);
        
        if (!$genre) {
            return response()->json(['message' => 'Género no encontrado'], 404);
        }

        $genre->delete();

        return response()->json(['message' => 'Género eliminado exitosamente']);
    }
}
