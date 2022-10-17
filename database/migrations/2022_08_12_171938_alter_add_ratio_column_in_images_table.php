<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddRatioColumnInImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            if (!Schema::hasColumn('images', 'ratio')) {
                $table->string('ratio', 10)->nullable()->after('folder');
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
        Schema::table('images', function (Blueprint $table) {
            if (Schema::hasColumn('images', 'ratio')) {
                $table->dropColumn('ratio');
            }
        });
    }
}
