<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberOnboardingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_onboardings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->date('created_at');
            $table->integer('onboarding_perentage')->default(0);
            $table->integer('count_applications')->default(0);
            $table->integer('count_accepted_applications')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_onboardings');
    }
}
