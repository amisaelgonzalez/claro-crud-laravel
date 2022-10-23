<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);
            $table->string('country_code', 2)->nullable();
            $table->string('fips_code', 255)->nullable();
            $table->string('iso2', 255)->nullable();
            $table->string('type', 191)->nullable();
            $table->decimal('latitude', 11,8)->nullable();
            $table->decimal('longitude', 11,8)->nullable();
            $table->boolean('flag')->default(1);
            $table->string('wikiDataId', 255)->nullable();

            $table->foreignId('country_id')->constrained();

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
        Schema::dropIfExists('states');
    }
}
