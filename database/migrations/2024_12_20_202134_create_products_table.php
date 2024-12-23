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

    Schema::create('products', function (Blueprint $table) {
        $table->id('id');
        $table->foreignId('category_id');
                   $table->foreignId(column: 'store_id');

        $table->string('name_EN');
        $table->string('name_AR');
        $table->text('Description')->nullable();
        $table->integer('Stock');


        $table->binary('image')->nullable();
        $table->integer('unit_price');
        $table->timestamps();

       /* $table->foreign('category_id')->references('id')->on('categories');
        $table->foreign('company_id')->references('id')->on('companies');*/

    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
