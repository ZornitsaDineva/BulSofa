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
        Schema::create('posts', function (Blueprint $table) {

            $table->increments('post_id');
            $table->integer('user_id')->unsigned();
            $table->integer('subcategory_id')->unsigned();
            //$table->integer('category_id')->unsigned();
            $table->integer('city_id')->unsigned();
            $table->string('ad_type');
            $table->string('ad_title');
            $table->string('item_condition');
            $table->float('item_price');
            $table->tinyInteger('price_negotiable')->default(0);
            //$table->string('brand')->nullable();
            //$table->string('model')->nullable();
            //$table->string('delivery')->nullable();
            $table->tinyInteger('status')->default(1); // 1 published, 0 unpublished
            $table->text('short_description',500);
            $table->text('long_description',5000);
            $table->integer('views')->default(0);
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
        Schema::dropIfExists('posts');
    }
}
