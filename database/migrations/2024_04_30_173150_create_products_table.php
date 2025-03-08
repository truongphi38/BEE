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
            //$table->unsignedBigInteger('status_id');
            
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('type_id');
            $table->decimal('discount_price', 10, 2)->nullable();         
            $table->timestamps();
            //$table->foreignId('type_id')->constrained('types')->onDelete('cascade')->after('category_id');

            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            //$table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

