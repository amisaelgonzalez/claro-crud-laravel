<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);
            $table->string('state_code', 255);
            $table->string('country_code', 2);
            $table->decimal('latitude', 11,8);
            $table->decimal('longitude', 11,8);
            $table->boolean('flag')->default(1);
            $table->string('wikiDataId', 255)->nullable();

            $table->foreignId('country_id')->constrained();
            $table->foreignId('state_id')->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
