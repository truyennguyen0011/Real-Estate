<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddAboutMeColumnInAdministratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('administrators', function (Blueprint $table) {
            if (!Schema::hasColumn('administrators', 'about_me')) {
                $table->text('about_me')->nullable()->after('role');
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
        Schema::table('administrators', function (Blueprint $table) {
            if (Schema::hasColumn('administrators', 'about_me')) {
                $table->dropColumn('about_me');
            }
        });
    }
}
