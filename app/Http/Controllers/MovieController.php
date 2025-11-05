<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Películas",
 *     description="Endpoints para gestión de películas"
 * )
 */
class MovieController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/movies",
     *     summary="Listar todas las películas",
     *     tags={"Películas"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Número de películas por página",
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
     *         description="Lista de películas obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="title", type="string"),
     *                     @OA\Property(property="release_date", type="string", format="date"),
     *                     @OA\Property(property="director_id", type="integer"),
     *                     @OA\Property(property="synopsis", type="string"),
     *                     @OA\Property(property="duration", type="integer"),
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
        $movies = Movie::paginate($perPage);
        return MovieResource::collection($movies)->response();
    }

    /**
     * @OA\Get(
     *     path="/api/movies/search",
     *     summary="Buscar películas por título, fecha de lanzamiento, director, reparto o género",
     *     tags={"Películas"},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Buscar por título de la película",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="release_date",
     *         in="query",
     *         description="Buscar por fecha de lanzamiento (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="director_id",
     *         in="query",
     *         description="Buscar por ID del director",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="actor_id",
     *         in="query",
     *         description="Buscar películas que contengan este actor en el reparto",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="genre_id",
     *         in="query",
     *         description="Buscar películas que pertenezcan a este género",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Número de películas por página",
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
     *         description="Lista de películas encontrada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="title", type="string"),
     *                     @OA\Property(property="release_date", type="string", format="date"),
     *                     @OA\Property(property="director_id", type="integer"),
     *                     @OA\Property(property="synopsis", type="string"),
     *                     @OA\Property(property="duration", type="integer"),
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
     *         response=422,
     *         description="Error de validación en los parámetros de búsqueda"
     *     )
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $query = Movie::query();

        // Búsqueda por título (búsqueda parcial, case insensitive)
        if ($request->has('title') && !empty($request->title)) {
            $query->where('title', 'ILIKE', '%' . $request->title . '%');
        }

        // Búsqueda por fecha de lanzamiento exacta
        if ($request->has('release_date') && !empty($request->release_date)) {
            $query->whereDate('release_date', $request->release_date);
        }

        // Búsqueda por director
        if ($request->has('director_id') && !empty($request->director_id)) {
            $query->where('director_id', $request->director_id);
        }

        // Búsqueda por actor en el reparto
        if ($request->has('actor_id') && !empty($request->actor_id)) {
            $query->whereHas('actors', function ($q) use ($request) {
                $q->where('actor_id', $request->actor_id);
            });
        }

        // Búsqueda por género
        if ($request->has('genre_id') && !empty($request->genre_id)) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('genre_id', $request->genre_id);
            });
        }

        $perPage = $request->input('per_page', 15);
        $movies = $query->paginate($perPage);

        return MovieResource::collection($movies)->response();
    }

    /**
     * @OA\Post(
     *     path="/api/movies",
     *     summary="Crear nueva película (Solo admin)",
     *     tags={"Películas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","release_date","director_id","duration"},
     *             @OA\Property(property="title", type="string", maxLength=255, example="The Matrix"),
     *             @OA\Property(property="release_date", type="string", format="date", example="1999-03-31"),
     *             @OA\Property(property="director_id", type="integer", example=1),
     *             @OA\Property(property="synopsis", type="string", example="A computer hacker learns about the true nature of reality."),
     *             @OA\Property(property="duration", type="integer", example=136)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Película creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="release_date", type="string", format="date"),
     *             @OA\Property(property="director_id", type="integer"),
     *             @OA\Property(property="synopsis", type="string"),
     *             @OA\Property(property="duration", type="integer"),
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
    public function store(StoreMovieRequest $request): JsonResponse
    {
        $posterPath = null;

        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        $movie = Movie::create([
            'title' => $request->title,
            'release_date' => $request->release_date,
            'director_id' => $request->director_id,
            'synopsis' => $request->synopsis,
            'duration' => $request->duration,
            'poster_path' => $posterPath,
        ]);

        return (new MovieResource($movie))->response()->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/api/movies/{id}",
     *     summary="Mostrar una película específica con detalles opcionales",
     *     tags={"Películas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la película",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="include",
     *         in="query",
     *         description="Incluir relaciones relacionadas (director,cast,genres,reviews)",
     *         required=false,
     *         @OA\Schema(type="string", example="director,cast,reviews")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Película encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="release_date", type="string", format="date"),
     *             @OA\Property(property="director_id", type="integer"),
     *             @OA\Property(property="synopsis", type="string"),
     *             @OA\Property(property="duration", type="integer"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *             @OA\Property(
     *                 property="director",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string")
     *             ),
     *             @OA\Property(
     *                 property="cast",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="character_name", type="string")
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="reviews",
     *                 type="object",
     *                 @OA\Property(property="count", type="integer"),
     *                 @OA\Property(property="average_rating", type="number"),
     *                 @OA\Property(
     *                     property="data",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="user_id", type="integer"),
     *                         @OA\Property(property="rating", type="integer"),
     *                         @OA\Property(property="comment", type="string"),
     *                         @OA\Property(property="created_at", type="string", format="date-time")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Película no encontrada"
     *     )
     * )
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        // Load relationships if requested
        if ($request->has('include')) {
            $includes = explode(',', $request->include);

            if (in_array('director', $includes)) {
                $movie->load('director');
            }

            if (in_array('cast', $includes)) {
                $movie->load('actors');
            }

            if (in_array('genres', $includes)) {
                $movie->load('genres');
            }

            if (in_array('reviews', $includes)) {
                $movie->load('reviews');
            }
        }

        return (new MovieResource($movie))->response();
    }

    /**
     * @OA\Put(
     *     path="/api/movies/{id}",
     *     summary="Actualizar una película",
     *     tags={"Películas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la película",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","release_date","director_id","duration"},
     *             @OA\Property(property="title", type="string", maxLength=255, example="The Matrix Reloaded"),
     *             @OA\Property(property="release_date", type="string", format="date", example="2003-05-15"),
     *             @OA\Property(property="director_id", type="integer", example=1),
     *             @OA\Property(property="synopsis", type="string", example="Neo and his allies continue their fight against the machines."),
     *             @OA\Property(property="duration", type="integer", example=138)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Película actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="release_date", type="string", format="date"),
     *             @OA\Property(property="director_id", type="integer"),
     *             @OA\Property(property="synopsis", type="string"),
     *             @OA\Property(property="duration", type="integer"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Película no encontrada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function update(UpdateMovieRequest $request, string $id): JsonResponse
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        $posterPath = $movie->poster_path;

        if ($request->hasFile('poster')) {
            // Delete old poster if exists
            if ($posterPath && Storage::disk('public')->exists('posters/' . $posterPath)) {
                Storage::disk('public')->delete('posters/' . $posterPath);
            }
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        $movie->update([
            'title' => $request->title,
            'release_date' => $request->release_date,
            'director_id' => $request->director_id,
            'synopsis' => $request->synopsis,
            'duration' => $request->duration,
            'poster_path' => $posterPath,
        ]);

        return (new MovieResource($movie))->response();
    }

    /**
     * @OA\Delete(
     *     path="/api/movies/{id}",
     *     summary="Eliminar una película (Solo admin)",
     *     tags={"Películas"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la película",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Película eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Película eliminada exitosamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Acceso denegado - Se requieren permisos de administrador"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Película no encontrada"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        // Delete poster file if exists
        if ($movie->poster_path && Storage::disk('public')->exists('posters/' . $movie->poster_path)) {
            Storage::disk('public')->delete('posters/' . $movie->poster_path);
        }

        $movie->delete();

        return response()->json(['message' => 'Película eliminada exitosamente']);
    }

    /**
     * @OA\Get(
     *     path="/api/movies/popular",
     *     summary="Obtener películas populares ordenadas por cantidad de reseñas",
     *     tags={"Películas"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Número máximo de películas a retornar",
     *         required=false,
     *         @OA\Schema(type="integer", default=10, maximum=50)
     *     ),
     *     @OA\Parameter(
     *         name="include",
     *         in="query",
     *         description="Incluir información adicional (director,cast,reviews)",
     *         required=false,
     *         @OA\Schema(type="string", example="director,reviews")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Películas populares obtenidas exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="release_date", type="string", format="date"),
     *                 @OA\Property(property="director_id", type="integer"),
     *                 @OA\Property(property="synopsis", type="string"),
     *                 @OA\Property(property="duration", type="integer"),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 @OA\Property(
     *                     property="reviews_summary",
     *                     type="object",
     *                     @OA\Property(property="count", type="integer"),
     *                     @OA\Property(property="average_rating", type="number")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getPopular(Request $request): JsonResponse
    {
        $limit = min($request->input('limit', 10), 50); // Máximo 50 películas

        $movies = Movie::withCount('reviews')
            ->with(['reviews' => function ($query) {
                $query->selectRaw('movie_id, AVG(rating) as average_rating')
                      ->groupBy('movie_id');
            }])
            ->orderBy('reviews_count', 'desc')
            ->orderByRaw('(SELECT AVG(rating) FROM reviews WHERE reviews.movie_id = movies.movie_id) DESC')
            ->limit($limit)
            ->get();

        // Load additional relationships if requested
        if ($request->has('include')) {
            $includes = explode(',', $request->include);

            if (in_array('director', $includes)) {
                $movies->load('director');
            }

            if (in_array('cast', $includes)) {
                $movies->load('actors');
            }

            if (in_array('reviews', $includes)) {
                $movies->load('reviews');
            }
        }

        // Transform the data to include reviews summary
        $movies->transform(function ($movie) {
            $movieArray = $movie->toArray();
            $movieArray['reviews_summary'] = [
                'count' => $movie->reviews_count,
                'average_rating' => $movie->reviews->avg('rating') ?? 0
            ];
            return $movieArray;
        });

        return response()->json($movies);
    }

    /**
     * @OA\Get(
     *     path="/api/movies/{id}/statistics",
     *     summary="Obtener estadísticas de una película específica",
     *     tags={"Películas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la película",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estadísticas de la película obtenidas exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="movie_id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(
     *                 property="statistics",
     *                 type="object",
     *                 @OA\Property(property="total_reviews", type="integer"),
     *                 @OA\Property(property="average_rating", type="number"),
     *                 @OA\Property(property="rating_distribution", type="object",
     *                     @OA\Property(property="1", type="integer"),
     *                     @OA\Property(property="2", type="integer"),
     *                     @OA\Property(property="3", type="integer"),
     *                     @OA\Property(property="4", type="integer"),
     *                     @OA\Property(property="5", type="integer")
     *                 ),
     *                 @OA\Property(property="recent_reviews_count", type="integer"),
     *                 @OA\Property(property="last_review_date", type="string", format="date-time", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Película no encontrada"
     *     )
     * )
     */
    public function getStatistics(string $id): JsonResponse
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Película no encontrada'], 404);
        }

        $reviews = $movie->reviews;

        // Calcular distribución de calificaciones
        $ratingDistribution = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
        ];

        foreach ($reviews as $review) {
            $ratingDistribution[$review->rating]++;
        }

        // Calcular reseñas recientes (últimos 30 días)
        $recentReviewsCount = $reviews->where('created_at', '>=', now()->subDays(30))->count();

        // Fecha de la última reseña
        $lastReviewDate = $reviews->max('created_at');

        $statistics = [
            'movie_id' => $movie->movie_id,
            'title' => $movie->title,
            'statistics' => [
                'total_reviews' => $reviews->count(),
                'average_rating' => $reviews->avg('rating') ?? 0,
                'rating_distribution' => $ratingDistribution,
                'recent_reviews_count' => $recentReviewsCount,
                'last_review_date' => $lastReviewDate,
            ],
        ];

        return response()->json($statistics);
    }
}
