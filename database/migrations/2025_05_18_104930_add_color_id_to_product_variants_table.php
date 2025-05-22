<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('product_variants', 'color_id')) {
            Schema::table('product_variants', function (Blueprint $table) {
                $table->unsignedBigInteger('color_id')->nullable()->after('size');
                $table->foreign('color_id')->references('id')->on('colors')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // Nếu bạn biết chính xác tên FK, dùng tên đó
            $table->dropForeign('product_variants_color_id_foreign');
            $table->dropColumn('color_id');
        });
    }
    
    
};
