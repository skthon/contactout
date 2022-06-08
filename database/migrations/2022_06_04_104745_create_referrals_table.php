<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->comment('Globally unique identifier for a referral');
            $table->uuid('referrer_uuid')->nullable()->comment('Globally Unique identifier for user');
            $table->string('referred_email')->unique();
            $table->boolean('status')->nullable()->default(false);
            $table->foreign('referrer_uuid')
                ->references('uuid')->on('users')
                ->onDelete('SET NULL')
                ->onUpdate('no action');

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
        Schema::dropIfExists('referrals');
    }
}
