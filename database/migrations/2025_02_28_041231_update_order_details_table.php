<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            
            $table->dropColumn('discount');

            // Thêm cột promotions_id và tạo khóa ngoại
            //$table->unsignedBigInteger('promotions_id')->nullable()->after('total_price');
            $table->foreign('promotions_id')->references('id')->on('promotions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Thêm lại cột product_name
            $table->string('product_name', 255)->after('total_price');

            // Xóa cột promotions_id và khóa ngoại
            $table->dropForeign(['promotions_id']);
            $table->dropColumn('promotions_id');
        });
    }
};

