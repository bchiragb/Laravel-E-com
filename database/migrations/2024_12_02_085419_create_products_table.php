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
            $table->id();
            $table->string('img1');
            $table->string('img2');
            $table->string('video')->nullable();
            $table->string('title');
            $table->string('sku');
            $table->integer('category')->unsigned();
            $table->integer('brand')->unsigned()->nullable();
            $table->string('tag');
            $table->integer('stock')->unsigned()->comment('0-outofstock');
            $table->string('sdesc');
            $table->longText('ldesc')->nullable();
            $table->longText('info')->nullable();
            $table->integer('ptype')->unsigned()->comment('1-simple,2-variant');
            $table->integer('rprice')->unsigned()->nullable();
            $table->integer('sprice')->unsigned()->nullable();
            $table->date('stdt');
            $table->date('eddt');
            $table->boolean('sts');
            $table->timestamps();
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
