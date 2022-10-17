<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->boolean('active')->default(false);
            $table->string('address');
            $table->string('price', 64);
            $table->string('area', 128);
            $table->string('phone_seller', 16);
            $table->string('direction', 32);
            $table->text('description');
            $table->foreignId('category_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('administrator_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('city_id', 5)->nullable();
            $table->string('district_id', 5)->nullable();
            $table->string('commune_id', 5)->nullable();
            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreign('commune_id')
                ->references('id')
                ->on('communes')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('published_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
