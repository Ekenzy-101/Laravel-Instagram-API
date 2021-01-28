<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ig_comment_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid("comment_id")->constrained("ig_post_comments")->onDelete('cascade');
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
        Schema::dropIfExists('ig_comment_likes');
    }
}
