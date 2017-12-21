<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCommentsTable extends Migration
{
    public function up()
    {
        $this->schema->create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id');
            $table->integer('user_id');
            $table->string('body');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('comments');
    }
}
