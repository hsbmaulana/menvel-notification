<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {

            $table->uuid('id');
            $table->string('type');
            $table->morphs('notifiable');

            $table->text('data');

            $table->timestamp('read_at')->nullable(true);
            $table->timestamp('created_at')->nullable(true);
            $table->timestamp('updated_at')->nullable(true);

            $table->primary('id');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}