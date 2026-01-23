<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE properties MODIFY gallery_images LONGTEXT NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE properties ALTER COLUMN gallery_images TYPE TEXT');
            DB::statement('ALTER TABLE properties ALTER COLUMN gallery_images DROP NOT NULL');
            return;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement('ALTER TABLE properties MODIFY gallery_images TEXT NULL');
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE properties ALTER COLUMN gallery_images TYPE TEXT');
            DB::statement('ALTER TABLE properties ALTER COLUMN gallery_images DROP NOT NULL');
            return;
        }
    }
};
