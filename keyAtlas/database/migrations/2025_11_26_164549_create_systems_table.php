<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Windows, Linux, macOS
            $table->string('version')->nullable(); // Opcional: 10, 11, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('systems');
    }
};
