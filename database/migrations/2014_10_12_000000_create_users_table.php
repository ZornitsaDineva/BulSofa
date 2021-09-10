<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            /* Required on Sign Up */
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            /* Can Give Later */
            $table->text('info')->nullable();
            $table->string('mobile')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('user_type')->default(0);
            $table->tinyInteger('account_status')->default(1);
            $table->integer('user_balance')->default(0);
        });

        /*DB::table('users')->insert(
            array(
                'id' => 0,
                'name' => 'Administrator',
                'email' => 'admin@BulSofa.com',
                'password' => md5('root1234'),
                'created_at' => date('Y-m-d 01:10:11')
            )
        );*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
