<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->date('visited_at');
            $table->integer('visit_count')->default(1);
            $table->timestamps();

            // Đảm bảo mỗi IP chỉ có 1 dòng duy nhất mỗi ngày
            $table->unique(['ip_address', 'visited_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('visitors');
    }
};
