<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReplyCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ig_reply_comments', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("content", 300);
            $table->foreignUuid("comment_id")->constrained("ig_post_comments")->onDelete('cascade');
            $table->foreignUuid("post_id")->constrained("ig_posts")->onDelete('cascade');
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
        Schema::dropIfExists('ig_reply_comments');
    }
}
