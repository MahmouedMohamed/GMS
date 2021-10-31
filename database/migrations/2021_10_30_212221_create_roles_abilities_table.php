<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesAbilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_abilities', function (Blueprint $table) {
            $table->primary(['role_id', 'ability_id']);
            $table->unique(['role_id', 'ability_id']);
            // $table->uuid('role_id');
            // $table->uuid('ability_id');
            $table->uuid('role_id')
                ->references('id')
                ->on('roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->uuid('ability_id')
                ->references('id')
                ->on('abilities')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('roles_abilities');
    }
}
