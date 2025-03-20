<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('wishlist', function (Blueprint $table) {
            $table->unsignedInteger('favorite_count')->default(1); 
        });
    }

    public function down()
    {
        Schema::table('wishlist', function (Blueprint $table) {
            $table->dropColumn('favorite_count');
        });
    }
};
