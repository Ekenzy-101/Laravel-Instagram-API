<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReplyLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ig_reply_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid("reply_id")->constrained("ig_reply_comments")->onDelete('cascade');
            $table->foreignUuid("user_id")->constrained("ig_users")->onDelete('cascade');
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
        Schema::dropIfExists('ig_reply_likes');
    }
}
