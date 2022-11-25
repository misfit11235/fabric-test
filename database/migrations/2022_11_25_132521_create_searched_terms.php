<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('searched_terms', function (Blueprint $table) {
            $table->id();
            $table->string('term');
            $table->unsignedInteger('total_pages');
            $table->unsignedInteger('last_cached_page');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('searched_terms');
    }
};
