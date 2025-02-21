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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id'); // Khóa ngoại kết nối với bảng orders
            $table->string('method', 50);
            $table->decimal('amount', 10, 2);
            $table->dateTime('payment_date')->nullable();
            $table->unsignedBigInteger('status_id'); // Khóa ngoại kết nối với bảng payment_statuses
            $table->timestamps();

            // Định nghĩa khóa ngoại
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
