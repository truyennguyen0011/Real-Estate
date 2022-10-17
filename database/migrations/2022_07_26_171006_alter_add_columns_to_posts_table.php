<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddColumnsToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'name_seller')) {
                $table->string('name_seller', 128)->nullable()->after('area');
            }
            if (!Schema::hasColumn('posts', 'email_seller')) {
                $table->string('email_seller', 128)->nullable()->after('phone_seller');
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
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'name_seller')) {
                $table->dropColumn('name_seller');
            }
            if (Schema::hasColumn('posts', 'email_seller')) {
                $table->dropColumn('email_seller');
            }
        });
    }
}
