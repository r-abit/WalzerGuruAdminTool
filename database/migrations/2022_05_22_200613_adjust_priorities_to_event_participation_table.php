<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_participations', function (Blueprint $table) {
            $table->dropColumn('age');
            $table->dropColumn('level');
            $table->dropColumn('height');
            $table->json('priorities')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_participation', function (Blueprint $table) {
            //
        });
    }
};
