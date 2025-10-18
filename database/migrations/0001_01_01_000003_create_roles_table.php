<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->string('role_code', 5)->primary();
            $table->string('role_name', 50);
            $table->timestamps();
        });   
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};