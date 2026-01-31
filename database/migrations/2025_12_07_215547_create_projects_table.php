<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('location');
            $table->enum('status', ['sale', 'available', 'under_construction']);
            $table->string('main_image')->nullable();
            $table->longText('description')->nullable();

            // overview
            $table->integer('overview_bedrooms')->nullable();
            $table->integer('overview_bathrooms')->nullable();
            $table->integer('overview_kitchens')->nullable();
            $table->integer('area')->nullable();

            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
