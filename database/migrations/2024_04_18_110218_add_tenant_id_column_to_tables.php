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
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained('companies');
        });
        Schema::table('tags', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained('companies');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained('companies');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
