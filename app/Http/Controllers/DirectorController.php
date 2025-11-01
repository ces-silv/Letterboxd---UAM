<?php

namespace App\Http\Controllers;

use App\Models\Director;
use App\Http\Requests\StoreDirectorRequest;
use App\Http\Requests\UpdateDirectorRequest;
use App\Http\Resources\DirectorResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Directores",
 *     description="Endpoints para gestión de directores de películas"
 * )
 */
class DirectorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/directors",
     *     summary="Listar todos los directores",
     *     tags={"Directores"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Número de directores por página",
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
     *         description="Lista de directores obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="director_id", type="integer"),
     *                     @OA\Property(property="director_name", type="string"),
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
        $directors = Director::paginate($perPage);
        return DirectorResource::collection($directors)->response();
    }

    /**
     * @OA\Post(
     *     path="/api/directors",
     *     summary="Crear nuevo director (Solo admin)",
     *     tags={"Directores"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"director_name"},
     *             @OA\Property(property="director_name", type="string", maxLength=50, example="Christopher Nolan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Director creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="director_id", type="integer"),
     *             @OA\Property(property="director_name", type="string"),
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
    public function store(StoreDirectorRequest $request): JsonResponse
    {
        $director = Director::create([
            'director_name' => $request->director_name,
        ]);

        return (new DirectorResource($director))->response()->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/api/directors/{id}",
     *     summary="Mostrar un director específico",
     *     tags={"Directores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del director",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Director encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="director_id", type="integer"),
     *             @OA\Property(property="director_name", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Director no encontrado"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $director = Director::find($id);

        if (!$director) {
            return response()->json(['message' => 'Director no encontrado'], 404);
        }

        return (new DirectorResource($director))->response();
    }

    /**
     * @OA\Put(
     *     path="/api/directors/{id}",
     *     summary="Actualizar un director",
     *     tags={"Directores"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del director",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"director_name"},
     *             @OA\Property(property="director_name", type="string", maxLength=50, example="Christopher Nolan")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Director actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="director_id", type="integer"),
     *             @OA\Property(property="director_name", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Director no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function update(UpdateDirectorRequest $request, string $id): JsonResponse
    {
        $director = Director::find($id);

        if (!$director) {
            return response()->json(['message' => 'Director no encontrado'], 404);
        }

        $director->update([
            'director_name' => $request->director_name,
        ]);

        return (new DirectorResource($director))->response();
    }

    /**
     * @OA\Delete(
     *     path="/api/directors/{id}",
     *     summary="Eliminar un director (Solo admin)",
     *     tags={"Directores"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del director",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Director eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Director eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Acceso denegado - Se requieren permisos de administrador"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Director no encontrado"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $director = Director::find($id);

        if (!$director) {
            return response()->json(['message' => 'Director no encontrado'], 404);
        }

        $director->delete();

        return response()->json(['message' => 'Director eliminado exitosamente']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }
}
