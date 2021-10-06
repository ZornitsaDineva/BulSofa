<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyConstrains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->foreign('parent_category_id')->references('category_id')->on('categories');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->foreign('division_id')->references('division_id')->on('divisions');

        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('subcategory_id')->references('subcategory_id')->on('subcategories');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('city_id')->references('city_id')->on('cities');
        });

        Schema::table('postimages', function (Blueprint $table) {
            $table->foreign('post_id')->references('post_id')->on('posts');
        });

        Schema::table('reports', function (Blueprint $table) {

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('post_id')->references('post_id')->on('posts');

        });

        Schema::table('messages', function (Blueprint $table) {

            $table->foreign('sender_id')->references('id')->on('users')->default(0);
            $table->foreign('receiver_id')->references('id')->on('users')->default(0);

        });

        Schema::table('featureds', function (Blueprint $table) {

            $table->foreign("post_id")->references('post_id')->on('posts');

        });

        Schema::table('recharge_requests', function (Blueprint $table) {

            $table->foreign('user_id')->references('id')->on('users');

        });

        Schema::table('admin_messages', function (Blueprint $table) {

            $table->foreign('sender_id')->references('id')->on('users');

        });

        Schema::table('users', function (Blueprint $table) {

            $table->foreign('city_id')->references('city_id')->on('cities');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropForeign('parent_category_id');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign('division_id');
        });

        Schema::table('posts', function (Blueprint $table) {
           $table->dropForeign('subcategory_id');
           $table->dropForeign('user_id');
           $table->dropForeign('city_id');
        });

        Schema::table('postimages', function (Blueprint $table) {
            $table->dropForeign('post_id');
        });

        Schema::table('reports', function (Blueprint $table) {

            $table->dropForeign('user_id');
            $table->dropForeign('post_id');

        });

        Schema::table('favourites', function (Blueprint $table) {

            $table->dropForeign('user_id');
            $table->dropForeign('post_id');

        });

        Schema::table('messages', function (Blueprint $table) {

            $table->dropForeign('sender_id');
            $table->dropForeign('receiver_id');

        });

        Schema::table('featureds', function (Blueprint $table) {

            $table->dropForeign("post_id");

        });

        Schema::table('recharge_requests', function (Blueprint $table) {

            $table->dropForeign('user_id');

        });

        Schema::table('admin_messages', function (Blueprint $table) {

            $table->dropForeign('sender_id');

        });

        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign('city_id');

        });
    }
}
