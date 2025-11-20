<?php

namespace App\Http\Controllers;

use App\Models\MovieCast;
use App\Http\Requests\StoreMovieCastRequest;
use App\Http\Requests\UpdateMovieCastRequest;
use App\Http\Resources\MovieCastResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Reparto de Películas",
 *     description="Endpoints para gestión del reparto de películas"
 * )
 */
class MovieCastController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/movie-casts",
     *     summary="Listar todo el reparto de películas",
     *     tags={"Reparto de Películas"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Número de registros por página",
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
     *         description="Lista de reparto obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="movie_id", type="integer"),
     *                     @OA\Property(property="actor_id", type="integer"),
     *                     @OA\Property(property="character_name", type="string")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="actual", type="integer"),
     *                 @OA\Property(property="pages", type="integer"),
     *                 @OA\Property(property="index", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $movieCasts = MovieCast::paginate($perPage);
        $data = collect($movieCasts->items())->map(fn ($cast) => (new MovieCastResource($cast))->toArray($request));
        return response()->json([
            'data' => $data,
            'pagination' => [
                'total' => $movieCasts->total(),
                'actual' => $movieCasts->currentPage(),
                'pages' => $movieCasts->lastPage(),
                'index' => $movieCasts->perPage(),
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/movie-casts",
     *     summary="Crear nuevo registro de reparto (Solo admin)",
     *     tags={"Reparto de Películas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"movie_id","actor_id","character_name"},
     *             @OA\Property(property="movie_id", type="integer", example=1),
     *             @OA\Property(property="actor_id", type="integer", example=1),
     *             @OA\Property(property="character_name", type="string", maxLength=255, example="Neo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registro de reparto creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="movie_id", type="integer"),
     *             @OA\Property(property="actor_id", type="integer"),
     *             @OA\Property(property="character_name", type="string"),
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
    public function store(StoreMovieCastRequest $request): JsonResponse
    {
        $movieCast = MovieCast::create([
            'movie_id' => $request->movie_id,
            'actor_id' => $request->actor_id,
            'character_name' => $request->character_name,
        ]);

        return (new MovieCastResource($movieCast))->response()->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/api/movie-casts/{id}",
     *     summary="Mostrar un registro de reparto específico",
     *     tags={"Reparto de Películas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del registro de reparto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro de reparto encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="movie_id", type="integer"),
     *             @OA\Property(property="actor_id", type="integer"),
     *             @OA\Property(property="character_name", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Registro de reparto no encontrado"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $movieCast = MovieCast::find($id);

        if (!$movieCast) {
            return response()->json(['message' => 'Registro de reparto no encontrado'], 404);
        }

        return (new MovieCastResource($movieCast))->response();
    }

    /**
     * @OA\Put(
     *     path="/api/movie-casts/{id}",
     *     summary="Actualizar un registro de reparto",
     *     tags={"Reparto de Películas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del registro de reparto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"movie_id","actor_id","character_name"},
     *             @OA\Property(property="movie_id", type="integer", example=1),
     *             @OA\Property(property="actor_id", type="integer", example=1),
     *             @OA\Property(property="character_name", type="string", maxLength=255, example="Neo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro de reparto actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="movie_id", type="integer"),
     *             @OA\Property(property="actor_id", type="integer"),
     *             @OA\Property(property="character_name", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Registro de reparto no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function update(UpdateMovieCastRequest $request, string $id): JsonResponse
    {
        $movieCast = MovieCast::find($id);

        if (!$movieCast) {
            return response()->json(['message' => 'Registro de reparto no encontrado'], 404);
        }

        $movieCast->update([
            'movie_id' => $request->movie_id,
            'actor_id' => $request->actor_id,
            'character_name' => $request->character_name,
        ]);

        return (new MovieCastResource($movieCast))->response();
    }

    /**
     * @OA\Delete(
     *     path="/api/movie-casts/{id}",
     *     summary="Eliminar un registro de reparto (Solo admin)",
     *     tags={"Reparto de Películas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del registro de reparto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro de reparto eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Registro de reparto eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Acceso denegado - Se requieren permisos de administrador"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Registro de reparto no encontrado"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $movieCast = MovieCast::find($id);

        if (!$movieCast) {
            return response()->json(['message' => 'Registro de reparto no encontrado'], 404);
        }

        $movieCast->delete();

        return response()->json(['message' => 'Registro de reparto eliminado exitosamente']);
    }
}