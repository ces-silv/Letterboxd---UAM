<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'movie_id')) {
                return;
            }
            $table->index('movie_id', 'reviews_movie_id_index');
            $table->index('user_id', 'reviews_user_id_index');
            $table->index(['movie_id', 'created_at'], 'reviews_movie_id_created_at_index');
        });

        Schema::table('movies', function (Blueprint $table) {
            if (!Schema::hasColumn('movies', 'director_id')) {
                return;
            }
            $table->index('director_id', 'movies_director_id_index');
            $table->index('release_date', 'movies_release_date_index');
            $table->index(['director_id', 'release_date'], 'movies_director_release_index');
            $table->index('title', 'movies_title_index');
        });

        Schema::table('movie_cast', function (Blueprint $table) {
            if (!Schema::hasColumn('movie_cast', 'movie_id')) {
                return;
            }
            $table->index('movie_id', 'movie_cast_movie_id_index');
            $table->index('actor_id', 'movie_cast_actor_id_index');
            $table->index(['movie_id', 'actor_id'], 'movie_cast_movie_actor_index');
            $table->index(['actor_id', 'movie_id'], 'movie_cast_actor_movie_index');
        });

        Schema::table('movie_genre', function (Blueprint $table) {
            if (!Schema::hasColumn('movie_genre', 'genre_id')) {
                return;
            }
            $table->index('genre_id', 'movie_genre_genre_id_index');
            $table->index(['genre_id', 'movie_id'], 'movie_genre_genre_movie_index');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS pg_trgm');
            DB::statement('CREATE INDEX IF NOT EXISTS idx_movies_title_trgm ON movies USING gin (title gin_trgm_ops)');
        }
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_movie_id_index');
            $table->dropIndex('reviews_user_id_index');
            $table->dropIndex('reviews_movie_id_created_at_index');
        });

        Schema::table('movies', function (Blueprint $table) {
            $table->dropIndex('movies_director_id_index');
            $table->dropIndex('movies_release_date_index');
            $table->dropIndex('movies_director_release_index');
            $table->dropIndex('movies_title_index');
        });

        Schema::table('movie_cast', function (Blueprint $table) {
            $table->dropIndex('movie_cast_movie_id_index');
            $table->dropIndex('movie_cast_actor_id_index');
            $table->dropIndex('movie_cast_movie_actor_index');
            $table->dropIndex('movie_cast_actor_movie_index');
        });

        Schema::table('movie_genre', function (Blueprint $table) {
            $table->dropIndex('movie_genre_genre_id_index');
            $table->dropIndex('movie_genre_genre_movie_index');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS idx_movies_title_trgm');
        }
    }
};
