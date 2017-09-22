<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFidMenuSortToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->integer('fid')->unsigned()->default(0)->comment('菜单父ID');
            $table->integer('is_menu')->unsigned()->default(0)->comment('是否菜单显示,[1|0]');
            $table->integer('sort')->unsigned()->default(0)->comment('排序');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->integer('fid')->unsigned()->default(0)->comment('菜单父ID');
            $table->integer('is_menu')->unsigned()->default(0)->comment('是否菜单显示,[1|0]');
            $table->integer('sort')->unsigned()->default(0)->comment('排序');
        });
    }
}
