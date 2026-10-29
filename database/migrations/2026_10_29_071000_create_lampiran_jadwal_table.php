<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lampiran_jadwal', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('jadwal_kuliah_id')
                ->constrained('jadwal_kuliah')
                ->cascadeOnDelete();
            $table->string('path');
            $table->string('nama_asli');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lampiran_jadwal');
    }
};
