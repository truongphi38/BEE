<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->string('name', 255);
            $table->string('img', 255)->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);

            // Dùng unsignedBigInteger để khớp với id của bảng khác
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');

            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('set null');

            $table->boolean('featured')->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();         
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

