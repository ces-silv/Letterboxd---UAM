<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
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
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Número de géneros por página",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de géneros obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="genre_id", type="integer"),
     *                     @OA\Property(property="genre_name", type="string"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="to", type="integer")
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="first", type="string"),
     *                 @OA\Property(property="last", type="string"),
     *                 @OA\Property(property="prev", type="string", nullable=true),
     *                 @OA\Property(property="next", type="string", nullable=true)
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $genres = Genre::paginate($perPage);
        return GenreResource::collection($genres)->response();
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

        return (new GenreResource($genre))->response()->setStatusCode(201);
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

        return (new GenreResource($genre))->response();
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

        return (new GenreResource($genre))->response();
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
