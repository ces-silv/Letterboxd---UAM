<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        DB::statement('CREATE VIRTUAL TABLE IF NOT EXISTS movies_fts USING fts5(title, synopsis)');

        $exists = Schema::hasTable('movies_fts');
        if ($exists) {
            DB::statement('INSERT INTO movies_fts(rowid, title, synopsis) SELECT movie_id, title, synopsis FROM movies');

            DB::statement('CREATE TRIGGER IF NOT EXISTS movies_ai AFTER INSERT ON movies BEGIN
                INSERT INTO movies_fts(rowid, title, synopsis) VALUES (new.movie_id, new.title, new.synopsis);
            END;');

            DB::statement('CREATE TRIGGER IF NOT EXISTS movies_ad AFTER DELETE ON movies BEGIN
                INSERT INTO movies_fts(movies_fts, rowid, title, synopsis) VALUES (\'delete\', old.movie_id, old.title, old.synopsis);
            END;');

            DB::statement('CREATE TRIGGER IF NOT EXISTS movies_au AFTER UPDATE ON movies BEGIN
                INSERT INTO movies_fts(movies_fts, rowid, title, synopsis) VALUES (\'delete\', old.movie_id, old.title, old.synopsis);
                INSERT INTO movies_fts(rowid, title, synopsis) VALUES (new.movie_id, new.title, new.synopsis);
            END;');
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            return;
        }

        DB::statement('DROP TRIGGER IF EXISTS movies_ai');
        DB::statement('DROP TRIGGER IF EXISTS movies_ad');
        DB::statement('DROP TRIGGER IF EXISTS movies_au');
        DB::statement('DROP TABLE IF EXISTS movies_fts');
    }
};
