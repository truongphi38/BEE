<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('price'); // Xóa cột price
            // Nếu muốn thêm hoặc sửa cột khác, có thể thêm tại đây
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable(); // Thêm lại cột price nếu rollback
        });
    }
};
