<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Usuario que reacciona
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); // Post donde reaccionó
            $table->enum('type', ['like', 'love', 'haha', 'sad', 'angry']); // Tipo de reacción
            $table->timestamps(); // Fechas de creación y actualización
        });
    }

    public function down()
    {
        Schema::dropIfExists('reactions');
    }
};

