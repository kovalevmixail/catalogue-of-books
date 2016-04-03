<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->engine = 'MyISAM';

            $table->increments('id');
            $table->string('name', 150);
            $table->string('author', 100);
            $table->integer('year')->nullable()->unsigned();
            $table->string('description', 2000);
            $table->string('cover', 200)->nullable(); //путь к файлу
            $table->timestamps();
        });

        DB::statement('ALTER TABLE books ADD FULLTEXT search(name, author)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function($table) {
            $table->dropIndex('search');
        });
        Schema::drop('books');
    }
}
