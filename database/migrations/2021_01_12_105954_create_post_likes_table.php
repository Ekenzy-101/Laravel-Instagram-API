<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ig_post_likes', function (Blueprint $table) {
            $table->id();
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
        Schema::dropIfExists('ig_post_likes');
    }
}
