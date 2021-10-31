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
            $table->primary(['client_id', 'gym_subscription_id']);
            // $table->uuid('client_id');
            // $table->uuid('gym_subscription_id');
            $table->uuid('client_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->uuid('gym_subscription_id')
                ->references('id')
                ->on('gym_subscriptions_info')
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
