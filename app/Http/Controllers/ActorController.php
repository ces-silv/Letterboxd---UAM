<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use App\Http\Requests\StoreActorRequest;
use App\Http\Requests\UpdateActorRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Actores",
 *     description="Endpoints para gestión de actores de películas"
 * )
 */
class ActorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/actors",
     *     summary="Listar todos los actores",
     *     tags={"Actores"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de actores obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="actor_id", type="integer"),
     *                 @OA\Property(property="actor_name", type="string"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $actors = Actor::all();
        return response()->json($actors);
    }

    /**
     * @OA\Post(
     *     path="/api/actors",
     *     summary="Crear nuevo actor (Solo admin)",
     *     tags={"Actores"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"actor_name"},
     *             @OA\Property(property="actor_name", type="string", maxLength=50, example="Keanu Reeves")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Actor creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="actor_id", type="integer"),
     *             @OA\Property(property="actor_name", type="string"),
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
    public function store(StoreActorRequest $request): JsonResponse
    {
        $actor = Actor::create([
            'actor_name' => $request->actor_name,
        ]);

        return response()->json($actor, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/actors/{id}",
     *     summary="Mostrar un actor específico",
     *     tags={"Actores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del actor",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actor encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="actor_id", type="integer"),
     *             @OA\Property(property="actor_name", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Actor no encontrado"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $actor = Actor::find($id);

        if (!$actor) {
            return response()->json(['message' => 'Actor no encontrado'], 404);
        }

        return response()->json($actor);
    }

    /**
     * @OA\Put(
     *     path="/api/actors/{id}",
     *     summary="Actualizar un actor",
     *     tags={"Actores"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del actor",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"actor_name"},
     *             @OA\Property(property="actor_name", type="string", maxLength=50, example="Keanu Reeves")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actor actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="actor_id", type="integer"),
     *             @OA\Property(property="actor_name", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Actor no encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function update(UpdateActorRequest $request, string $id): JsonResponse
    {
        $actor = Actor::find($id);

        if (!$actor) {
            return response()->json(['message' => 'Actor no encontrado'], 404);
        }

        $actor->update([
            'actor_name' => $request->actor_name,
        ]);

        return response()->json($actor);
    }

    /**
     * @OA\Delete(
     *     path="/api/actors/{id}",
     *     summary="Eliminar un actor (Solo admin)",
     *     tags={"Actores"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del actor",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actor eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Actor eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Acceso denegado - Se requieren permisos de administrador"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Actor no encontrado"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $actor = Actor::find($id);

        if (!$actor) {
            return response()->json(['message' => 'Actor no encontrado'], 404);
        }

        $actor->delete();

        return response()->json(['message' => 'Actor eliminado exitosamente']);
    }
}