<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriberEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriber_email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->boolean('email_status')->default(0);    // 1 => sent successfully, 0 => email sending failed
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
        Schema::dropIfExists('subscriber_email_logs');
    }
}
