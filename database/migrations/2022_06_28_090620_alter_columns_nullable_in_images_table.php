<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsNullableInImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            if (Schema::hasColumn('images', 'post_id')) {
                $table->unsignedBigInteger('post_id')->nullable()->change();
            }
            if (Schema::hasColumn('images', 'new_id')) {
                $table->unsignedBigInteger('new_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
