<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Reseñas",
 *     description="Endpoints para gestión de reseñas de películas"
 * )
 */
class ReviewController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reviews",
     *     summary="Listar todas las reseñas",
     *     tags={"Reseñas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Número de reseñas por página",
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
     *         description="Lista de reseñas obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="user_id", type="integer"),
     *                     @OA\Property(property="movie_id", type="integer"),
     *                     @OA\Property(property="rating", type="integer"),
     *                     @OA\Property(property="comment", type="string"),
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
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - Se requiere autenticación"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $reviews = Review::paginate($perPage);
        return ReviewResource::collection($reviews)->response();
    }

    /**
     * @OA\Get(
     *     path="/api/reviews/my-reviews",
     *     summary="Obtener las reseñas del usuario autenticado",
     *     tags={"Reseñas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Número de reseñas por página",
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
     *     @OA\Parameter(
     *         name="include",
     *         in="query",
     *         description="Incluir información de la película (movie)",
     *         required=false,
     *         @OA\Schema(type="string", example="movie")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reseñas del usuario obtenidas exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="user_id", type="integer"),
     *                     @OA\Property(property="movie_id", type="integer"),
     *                     @OA\Property(property="rating", type="integer"),
     *                     @OA\Property(property="comment", type="string"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                     @OA\Property(
     *                         property="movie",
     *                         type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="title", type="string"),
     *                         @OA\Property(property="release_date", type="string", format="date")
     *                     )
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
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - Se requiere autenticación"
     *     )
     * )
     */
    public function getMyReviews(Request $request): JsonResponse
    {
        $query = Review::where('user_id', Auth::id());

        // Include movie information if requested
        if ($request->has('include') && in_array('movie', explode(',', $request->include))) {
            $query->with('movie:id,movie_id,title,release_date');
        }

        $perPage = $request->input('per_page', 15);
        $reviews = $query->paginate($perPage);

        return ReviewResource::collection($reviews)->response();
    }

    /**
     * @OA\Get(
     *     path="/api/movies/{movieId}/reviews",
     *     summary="Listar todas las reseñas de una película específica",
     *     tags={"Reseñas"},
     *     @OA\Parameter(
     *         name="movieId",
     *         in="path",
     *         required=true,
     *         description="ID de la película",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Número de reseñas por página",
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
     *         description="Lista de reseñas de la película obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="user_id", type="integer"),
     *                     @OA\Property(property="movie_id", type="integer"),
     *                     @OA\Property(property="rating", type="integer"),
     *                     @OA\Property(property="comment", type="string"),
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
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Película no encontrada"
     *     )
     * )
     */
    public function getReviewsByMovie(Request $request, string $movieId): JsonResponse
    {
        // Verificar que la película existe
        $movieExists = \App\Models\Movie::find($movieId);
        if (!$movieExists) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        $perPage = $request->input('per_page', 15);
        $reviews = Review::where('movie_id', $movieId)->paginate($perPage);
        return ReviewResource::collection($reviews)->response();
    }

    /**
     * @OA\Post(
     *     path="/api/reviews",
     *     summary="Crear nueva reseña",
     *     tags={"Reseñas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"movie_id","rating"},
     *             @OA\Property(property="movie_id", type="integer", example=1),
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
     *             @OA\Property(property="comment", type="string", maxLength=1000, example="Excelente película, muy recomendable")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reseña creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="movie_id", type="integer"),
     *             @OA\Property(property="rating", type="integer"),
     *             @OA\Property(property="comment", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - Se requiere autenticación"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $review = Review::create([
            'user_id' => Auth::id(),
            'movie_id' => $request->movie_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return (new ReviewResource($review))->response()->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/api/reviews/{id}",
     *     summary="Mostrar una reseña específica",
     *     tags={"Reseñas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reseña",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reseña encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="movie_id", type="integer"),
     *             @OA\Property(property="rating", type="integer"),
     *             @OA\Property(property="comment", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - Se requiere autenticación"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reseña no encontrada"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Reseña no encontrada'], 404);
        }

        return (new ReviewResource($review))->response();
    }

    /**
     * @OA\Put(
     *     path="/api/reviews/{id}",
     *     summary="Actualizar una reseña",
     *     tags={"Reseñas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reseña",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"movie_id","rating"},
     *             @OA\Property(property="movie_id", type="integer", example=1),
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=4),
     *             @OA\Property(property="comment", type="string", maxLength=1000, example="Buena película, pero esperaba más")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reseña actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="movie_id", type="integer"),
     *             @OA\Property(property="rating", type="integer"),
     *             @OA\Property(property="comment", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - Se requiere autenticación"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Acceso denegado - Solo el propietario puede editar"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reseña no encontrada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function update(UpdateReviewRequest $request, string $id): JsonResponse
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Reseña no encontrada'], 404);
        }

        // Verificar que el usuario sea el propietario de la reseña
        if ($review->user_id !== Auth::id()) {
            return response()->json(['message' => 'No tienes permiso para editar esta reseña'], 403);
        }

        $review->update([
            'movie_id' => $request->movie_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return (new ReviewResource($review))->response();
    }

    /**
     * @OA\Delete(
     *     path="/api/reviews/{id}",
     *     summary="Eliminar una reseña",
     *     tags={"Reseñas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la reseña",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reseña eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reseña eliminada exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado - Se requiere autenticación"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Acceso denegado - Solo el propietario puede eliminar"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reseña no encontrada"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $review = Review::find($id);

        if (!$review) {
            return response()->json(['message' => 'Reseña no encontrada'], 404);
        }

        // Verificar que el usuario sea el propietario de la reseña
        if ($review->user_id !== Auth::id()) {
            return response()->json(['message' => 'No tienes permiso para eliminar esta reseña'], 403);
        }

        $review->delete();

        return response()->json(['message' => 'Reseña eliminada exitosamente']);
    }
}
