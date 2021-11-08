<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsGymSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_gym_subscriptions', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('client_id');
            $table->string('gym_subscription_plan_id');
            $table->foreign('client_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('gym_subscription_plan_id')
                ->references('id')
                ->on('gym_subscriptions_plans')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->dateTime('start');
            $table->dateTime('end');
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
        Schema::dropIfExists('clients_gym_subscriptions');
    }
}
