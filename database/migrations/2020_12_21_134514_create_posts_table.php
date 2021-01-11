<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ig_posts', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("caption", 2200);
            $table->string("location", 200);
            $table->foreignUuid("user_id")->constrained("ig_users")->onDelete('cascade');
            $table->json("image_urls");
            $table->json("keys");
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
        Schema::dropIfExists('ig_posts');
    }
}
