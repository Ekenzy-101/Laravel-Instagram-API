<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ig_users', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('username', 30)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('gender', 10);
            $table->string('phone_no', 20);
            $table->string('object_key');
            $table->string('image_url');
            $table->string('bio', 150);
            $table->string('website');
            $table->string('name', 50);
            $table->unsignedInteger('verification_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('ig_users');
    }
}
