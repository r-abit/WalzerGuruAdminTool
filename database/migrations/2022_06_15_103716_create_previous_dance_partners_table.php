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
        Schema::create('previous_dance_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('partner')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('previous_dance_partners', function (Blueprint $table) {
            $table->dropForeign('previous_dance_partners_user_foreign');
            $table->dropForeign('previous_dance_partners_partner_foreign');
            $table->dropForeign('previous_dance_partners_event_id_foreign');
        });
        Schema::dropIfExists('previous_dance_partners');
    }
};
