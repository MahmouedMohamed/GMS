<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainersClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trainers_clients', function (Blueprint $table) {
            $table->primary(['trainer_id', 'client_id']);
            // $table->uuid('trainer_id');
            // $table->uuid('client_id');
            // $table->uuid('trainer_subscription_id');
            $table->uuid('trainer_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->uuid('client_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->uuid('trainer_subscription_id')
                ->references('id')
                ->on('trainers_subscriptions_info')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->dateTime('session_from');
            $table->dateTime('session_to');
            $table->integer('left_sessions');
            $table->boolean('session_done')->default(0);
            $table->text('client_note')->nullable();
            $table->text('trainer_note')->nullable();
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
        Schema::dropIfExists('trainers_clients');
    }
}
