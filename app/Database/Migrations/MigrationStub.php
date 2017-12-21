<?php

use $useClassName;
use Illuminate\Database\Schema\Blueprint;

class $className extends $baseClassName
{
    public function up()
    {
        $this->schema->create('TABLE_NAME', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        $this->schema->table('TABLE_NAME', function (Blueprint $table) {
            $table->string();
        });
    }

    public function down()
    {
        $this->schema->drop('TABLE_NAME');

        $this->schema->table('TABLE_NAME', function (Blueprint $table) {
            $table->dropColumn();
        });
    }
}
