<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPostsTable extends Migration
{
    public function up()
    {
        $this->schema->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('body');
            $table->string('user_id');
            $table->string('slug');
            $table->boolean('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('posts');
    }
}
