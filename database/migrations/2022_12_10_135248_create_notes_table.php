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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('eleve_code')->references('code')->on('eleves')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('eleve_id')->nullable()->constrained('eleves')->onUpdate('cascade')->onDelete('cascade');
            $table->string('elementmodule_code')->references('code')->on('elementmodules')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('elementmodule_id')->nullable()->constrained('element_modules')->onUpdate('cascade')->onDelete('cascade');
            $table->float('note')->nullable();
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
        Schema::dropIfExists('notes');
    }
};
