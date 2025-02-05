<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('company_id')->unsigned()->nullable();

            $table
                ->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->bigInteger('company_id')
                ->unsigned()
                ->nullable()
                ->index()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->dropForeign('users_company_id_foreign');
            $table
                ->bigInteger('company_id')
                ->unsigned()
                ->index()
                ->change();
        });
    }
};
